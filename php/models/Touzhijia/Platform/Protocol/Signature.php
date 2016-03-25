<?php


/**
 * SHA1 class
 *
 * 计算公众平台的消息签名接口.
 */
class Touzhijia_Platform_Protocol_Signature
{
	/**
	 * 用SHA1算法生成安全签名
	 * @param string $token 票据
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 * @param string $encrypt 密文消息
	 */
	static public function getSign($token, $timestamp, $nonce, $encrypt_msg)
	{
		//排序
		try {
			$arrData = array($encrypt_msg, $token, $timestamp, $nonce);
			sort($arrData, SORT_STRING);
			$str = implode($arrData);
			$sign = sha1($str);
			return array(Touzhijia_Platform_Protocol_ErrorCode::OK, $sign);
		} catch (Exception $e) {
			//print $e . "\n";
			return array(Touzhijia_Platform_Protocol_ErrorCode::COMPUTE_SIGNATURE_ERROR, null);
		}
	}

}


