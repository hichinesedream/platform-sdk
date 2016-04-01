<?php

/**
 * 对与投之家通讯的消息进行加密解密
 *
 * 1. 合作平台回复加密消息给投之家
 * 2. 合作平台收到投之家发送的消息，验证消息的安全性，并对消息进行解密
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Protocol
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:51:52
 */
class Touzhijia_Platform_Protocol_MsgCrypter
{
	private $_token;
	private $_aeskey;
	private $_appId;

	/**
	 * 构造函数
	 *
	 * @param $token string  投之家协议中, 分配给合作平台的token
	 * @param $aeskey string 投之家协议中, 分配给合作平台的aes加密密钥
	 * @param $appId string  投之家协议中, 分配给合作平台的appid
	 */
	public function __construct($token, $aeskey, $appId)
	{
		$this->_token  = $token;
		$this->_aeskey = $aeskey;
		$this->_appId  = $appId;
	}

	/**
	 * 将消息加密打包
	 *
	 * 1. 对要发送的消息进行AES-CBC加密
	 * 2. 生成安全签名
	 * 3. 将消息密文和安全签名打包成投之家约定的json格式
	 *
	 * @param $rawdata string 待发送的消息，json格式的字符串
	 * @param $timeStamp string 时间戳, Unix 时间戳,即从 Unix 纪元（格林威治时间 1970-01-01 00:00:00）到当前时间的秒数
	 * @param $nonce string 随机字符串
	 * @return array(integer, string) array(错误码,加密结果)
	 */
	public function encrypt($rawdata, $timeStamp = null, $nonce = null)
	{
		// 格式化数据  RawData = RandomStr + DataLength + Data + PlatId;
		$rawdata = Touzhijia_Platform_Protocol_DataEncoder::encode($rawdata, $this->_appId);

		// 生成时间戳
		if (empty($timeStamp)) {
			$timeStamp = time();
		}
	
		// 生成扰码
		if (empty($nonce)) {
			$nonce = Touzhijia_Util_String::getRandomStr(4);
		}

		// 加密 EncryptData = AESEncrypt(RawData, ReqKey);
		$crypter = new Touzhijia_Platform_Security_AesCrypter($this->_getRequestKey($this->_aeskey, $timeStamp));
		$encryptedData = $crypter->encrypt($rawdata);

		//生成安全签名
		list($ret, $sign) = Touzhijia_Platform_Protocol_Signature::getSign($this->_token, $timeStamp, $nonce, $encryptedData);
		if ($ret != 0) {
			return array($ret, null);
		}

		$msg = new Touzhijia_Platform_Entity_Message();
		$msg->setData($encryptedData);
		$msg->setTimestamp($timeStamp);
		$msg->setNonce($nonce);
		$msg->setSignature($sign);
		
		$strOut = $msg->toJson();
		
		return array(Touzhijia_Platform_Protocol_ErrorCode::OK, $strOut);
	}


	/**
	 * 根据签名检验消息的真实性，并且获取解密后的明文
	 *
	 * @param $jsonString string 待接收的消息
	 * @return array(integer, string) array(错误码,解密结果)
	 */
	public function decrypt($jsonString)
	{
		// 提取元素
		$msg = new Touzhijia_Platform_Entity_Message();
		$ret = $msg->fromJson($jsonString);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			return array(Touzhijia_Platform_Protocol_ErrorCode::PARSE_JSON_ERROR, null);
		}

		// 时间戳与当前时间偏移5分钟
		if (abs(time() - $msg->getTimestamp()) > TZJ_PROTOCOL_EXPIRE_SEC) {
			return array(Touzhijia_Platform_Protocol_ErrorCode::VALIDATE_TIMESTAMP_ERROR, null);
		}

		//验证安全签名
		list($ret, $expectedSign) = Touzhijia_Platform_Protocol_Signature::getSign(
					$this->_token, 
					$msg->getTimestamp(),
					$msg->getNonce(),
					$msg->getData());
		if ($ret != 0) {
			return array($ret, null);
		}

		if ($expectedSign != $msg->getSignature()) {
			return array(Touzhijia_Platform_Protocol_ErrorCode::VALIDATE_SIGNATURE_ERROR, null);
		}

		// 解密 RawData = AESdecrypt(EncryptData, ReqKey);
		$crypter = new Touzhijia_Platform_Security_AesCrypter($this->_getRequestKey($this->_aeskey, $msg->getTimestamp()));
		$rawdata = $crypter->decrypt($msg->getData());

		// 反序列化数据  RawData = RandomStr + DataLength + Data + PlatId;
		list($rawdata, $appid) = Touzhijia_Platform_Protocol_DataEncoder::decode($rawdata);
		if ($appid != $this->_appId) {
			return array(Touzhijia_Platform_Protocol_ErrorCode::VALIDATE_APPID_ERROR, $rawdata);
		}

		return array(Touzhijia_Platform_Protocol_ErrorCode::OK, $rawdata);
	}


	/**
	 * 依据投之家协议, 根据aes密钥和时间戳, 动态生成aes加密解密的密钥
	 *
	 * @param string $key
	 * @param string $timeStamp
	 * @return string 密钥
	 */
	private function _getRequestKey($key, $timestamp)
	{
		return md5("${key}${timestamp}", true);
	}

}

