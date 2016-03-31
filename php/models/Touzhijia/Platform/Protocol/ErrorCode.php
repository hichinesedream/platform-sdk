<?php

/**
 * 投之家协议约定的全局错误码定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Protocol
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:47:49
 */
class Touzhijia_Platform_Protocol_ErrorCode
{
	// 正常
	CONST OK                       = 0;

	// 协议类错误
	CONST MISSING_SERVICE_NAME     = 101;
	CONST UNKNOWN_SERVICE_ERROR    = 102;
	CONST VALIDATE_SIGNATURE_ERROR = 103;
	CONST VALIDATE_TIMESTAMP_ERROR = 104;
	CONST VALIDATE_APPID_ERROR     = 105;
	CONST PARSE_JSON_ERROR         = 106;
	CONST GEN_RETURN_MSG_ERROR     = 107;
	CONST COMPUTE_SIGNATURE_ERROR  = 108;
	CONST ENCRYPT_AES_ERROR        = 109;
	CONST DECRYPT_AES_ERROR        = 110;

	// 参数类错误
	CONST INVALID_PARAMETER        = 201;
	const USER_NOT_EXISTS          = 202;
	const START_GREAT_THAN_END     = 203;
	const TIME_RANGE_EXCEED        = 204;
	const QUERY_ITEM_COUNT_EXCEED  = 205;

	// 应用层错误, 如DB gone away，tcp connect failed, network down
	CONST APPLICATION_ERROR        = 500;	

	// 业务类错误
	const TELEPHONE_HAVE_USED      = 1001;
	const EMAIL_HAVE_USED          = 1002;
	const IDCARD_HAVE_USED         = 1003;
	const USER_BIND_MISMATCH       = 5001;


	/**
	 * 根据全局错误码获取错误描述
	 *
	 * @param integer $code 错误码
	 * @param string 错误描述
	 */
	static public function getErrMsg($code)
	{
		$arrErrMsg = array(
			self::OK                       => 'OK',

			self::MISSING_SERVICE_NAME     => 'MISSING_SERVICE_NAME',
			self::UNKNOWN_SERVICE_ERROR    => 'UNKNOWN_SERVICE_ERROR',
			self::VALIDATE_SIGNATURE_ERROR => 'VALIDATE_SIGNATURE_ERROR',
			self::VALIDATE_APPID_ERROR     => 'VALIDATE_APPID_ERROR',
			self::VALIDATE_TIMESTAMP_ERROR => 'VALIDATE_TIMESTAMP_ERROR',
			self::PARSE_JSON_ERROR         => 'PARSE_JSON_ERROR',
			self::COMPUTE_SIGNATURE_ERROR  => 'COMPUTE_SIGNATURE_ERROR',
			self::ENCRYPT_AES_ERROR        => 'ENCRYPT_AES_ERROR',
			self::DECRYPT_AES_ERROR        => 'DECRYPT_AES_ERROR',

			self::INVALID_PARAMETER        => 'INVALID_PARAMETER',
			self::USER_NOT_EXISTS          => 'USER_NOT_EXISTS',
			self::START_GREAT_THAN_END     => 'START_GREAT_THAN_END',
			self::TIME_RANGE_EXCEED        => 'TIME_RANGE_EXCEED',
			self::QUERY_ITEM_COUNT_EXCEED  => 'QUERY_ITEM_COUNT_EXCEED',

			self::APPLICATION_ERROR        => 'APPLICATION_ERROR',

			self::TELEPHONE_HAVE_USED      => 'TELEPHONE_HAVE_USED',
			self::EMAIL_HAVE_USED          => 'EMAIL_HAVE_USED',
			self::IDCARD_HAVE_USED         => 'IDCARD_HAVE_USED',
		);

		if (isset($arrErrMsg[$code])) {
			return $arrErrMsg[$code];
		}

		return 'Unknown error';
	}
}

