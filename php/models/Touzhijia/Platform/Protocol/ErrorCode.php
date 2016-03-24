<?php


class Touzhijia_Platform_Protocol_ErrorCode
{
	// 正常
	CONST OK                       = 0;

	// 用户类
	const ERR_TELEPHONE_HAVE_USED  = 1001;
	const ERR_EMAIL_HAVE_USED      = 1002;
	const ERR_IDCARD_HAVE_USED     = 1003;
	const ERR_USER_NOT_EXISTS      = 4001;

	// 协议类
	CONST MISSING_SERVICE_NAME     = 9001;
	CONST UNKNOWN_SERVICE_ERROR    = 9002;
	CONST VALIDATE_SIGNATURE_ERROR = 9003;
	CONST VALIDATE_APPID_ERROR     = 9004;
	CONST VALIDATE_TIMESTAMP_ERROR = 9005;
	CONST PARSE_JSON_ERROR         = 9006;
	CONST GEN_RETURN_MSG_ERROR     = 9007;
	CONST COMPUTE_SIGNATURE_ERROR  = 9008;
	CONST ENCRYPT_AES_ERROR        = 9009;
	CONST DECRYPT_AES_ERROR        = 9010;

	// 调试类
	CONST TEST_ERROR               = 9999;

	static public function getErrMsg($code)
	{
		$arrErrMsg = array(
			self::OK                       => 'OK',
			self::ERR_TELEPHONE_HAVE_USED  => 'ERR_TELEPHONE_HAVE_USED',
			self::ERR_EMAIL_HAVE_USED      => 'ERR_EMAIL_HAVE_USED',
			self::ERR_IDCARD_HAVE_USED     => 'ERR_USER_NOT_EXISTS',

			self::MISSING_SERVICE_NAME     => 'MISSING_SERVICE_NAME',
			self::UNKNOWN_SERVICE_ERROR    => 'UNKNOWN_SERVICE_ERROR',
			self::VALIDATE_SIGNATURE_ERROR => 'VALIDATE_SIGNATURE_ERROR',
			self::VALIDATE_APPID_ERROR     => 'VALIDATE_APPID_ERROR',
			self::VALIDATE_TIMESTAMP_ERROR => 'VALIDATE_TIMESTAMP_ERROR',
			self::PARSE_JSON_ERROR         => 'PARSE_JSON_ERROR',
			self::COMPUTE_SIGNATURE_ERROR  => 'COMPUTE_SIGNATURE_ERROR',
			self::ENCRYPT_AES_ERROR        => 'ENCRYPT_AES_ERROR',
			self::DECRYPT_AES_ERROR        => 'DECRYPT_AES_ERROR',
			self::TEST_ERROR               => 'TEST_ERROR',
		);

		if (isset($arrErrMsg[$code])) {
			return $arrErrMsg[$code];
		}

		return 'Unknown error';
	}
}

