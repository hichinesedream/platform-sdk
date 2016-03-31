<?php

/**
 * 对与投之家通讯的请求响应包计算签名
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Protocol
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:44:29
 */
class Touzhijia_Platform_Protocol_Signature
{
	/**
	 * 用SHA1算法生成安全签名
	 *
	 * @param string $token 票据
	 * @param string $timestamp 时间戳
	 * @param string $nonce 随机字符串
	 * @param string $encrypt_msg 密文消息
	 * @return array(integer, string) 返回结果是一个数组, array(错误码, 签名结果)
	 */
	static public function getSign($token, $timestamp, $nonce, $encrypt_msg)
	{
		try {
			$arrData = array($encrypt_msg, $token, $timestamp, $nonce);
			sort($arrData, SORT_STRING);
			$str = implode($arrData);
			$sign = sha1($str);
			return array(Touzhijia_Platform_Protocol_ErrorCode::OK, $sign);

		} catch (Exception $e) {

			return array(Touzhijia_Platform_Protocol_ErrorCode::COMPUTE_SIGNATURE_ERROR, null);
		}
	}

}


