<?php

/**
 * 对公众平台发送给公众账号的消息加解密示例代码.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */


/**
 * 1.第三方回复加密消息给公众平台；
 * 2.第三方收到公众平台发送的消息，验证消息的安全性，并对消息进行解密。
 */
class Touzhijia_Platform_Protocol_MsgCrypter
{
	private $_token;
	private $_aeskey;
	private $_appId;

	/**
	 * 构造函数
	 * @param $token string 公众平台上，开发者设置的token
	 * @param $aeskey string 公众平台上，开发者设置的aeskey
	 * @param $appId string 公众平台的appId
	 */
	public function __construct($token, $aeskey, $appId)
	{
		$this->_token  = $token;
		$this->_aeskey = $aeskey;
		$this->_appId  = $appId;
	}

	/**
	 * 将公众平台回复用户的消息加密打包.
	 * <ol>
	 *    <li>对要发送的消息进行AES-CBC加密</li>
	 *    <li>生成安全签名</li>
	 *    <li>将消息密文和安全签名打包成xml格式</li>
	 * </ol>
	 *
	 * @param $rawdata string 公众平台待回复用户的消息，xml格式的字符串
	 * @param $timeStamp string 时间戳，可以自己生成，也可以用URL参数的timestamp
	 * @param $nonce string 随机串，可以自己生成，也可以用URL参数的nonce
	 *
	 * @return int 成功0，失败返回对应的错误码
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
			$nonce = Touzhijia_Platform_Util_String::getRandomStr(4);
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
		
		$strOut = $msg->toString();
		
		return array(Touzhijia_Platform_Security_ErrorCode::$OK, $strOut);
	}


	/**
	 * 检验消息的真实性，并且获取解密后的明文.
	 * <ol>
	 *    <li>利用收到的密文生成安全签名，进行签名验证</li>
	 *    <li>若验证通过，则提取xml中的加密消息</li>
	 *    <li>对消息进行解密</li>
	 * </ol>
	 *
	 * @param $msgSignature string 签名串，对应URL参数的msg_signature
	 * @param $timestamp string 时间戳 对应URL参数的timestamp
	 * @param $nonce string 随机串，对应URL参数的nonce
	 * @param $postData string 密文，对应POST请求的数据
	 * @param &$msg string 解密后的原文，当return返回0时有效
	 *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function decrypt($strJson)
	{
		// 提取元素
		$msg = new Touzhijia_Platform_Entity_Message();
		$ret = $msg->parseFromJson($strJson);
		if ($ret === false) {
			return array(Touzhijia_Platform_Security_ErrorCode::$ParseJsonError, null);
		}

		// 时间戳与当前时间偏移5分钟
		if (abs(time() - $msg->getTimestamp()) > 300) {
			return array(Touzhijia_Platform_Security_ErrorCode::$ValidateTimeStampError, null);
		}

		//验证安全签名
		list($ret, $expected_sign) = Touzhijia_Platform_Protocol_Signature::getSign(
					$this->_token, 
					$msg->getTimestamp(),
					$msg->getNonce(),
					$msg->getData());
		if ($ret != 0) {
			return array($ret, null);
		}

		if ($expected_sign != $msg->getSignature()) {
			return array(Touzhijia_Platform_Security_ErrorCode::$ValidateSignatureError, null);
		}

		// 解密 RawData = AESdecrypt(EncryptData, ReqKey);
		$crypter = new Touzhijia_Platform_Security_AesCrypter($this->_getRequestKey($this->_aeskey, $msg->getTimestamp()));
		$rawdata = $crypter->decrypt($msg->getData());

		// 反序列化数据  RawData = RandomStr + DataLength + Data + PlatId;
		list($rawdata, $appid) = Touzhijia_Platform_Protocol_DataEncoder::decode($rawdata);
		if ($appid != $this->_appId) {
			return array(Touzhijia_Platform_Security_ErrorCode::$ValidateAppidError, $rawdata);
		}

		return array(Touzhijia_Platform_Security_ErrorCode::$OK, $rawdata);
	}

	private function _getRequestKey($key, $timestamp)
	{
		return md5("${key}${timestamp}");
	}

}

