<?php

/**
 * 错误响应包定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:57:53
 */
class Touzhijia_Platform_Entity_ErrRes extends Touzhijia_Platform_Entity_BaseMsg
{
	public function __construct($errcode = null)
	{
		parent::__construct();

		if (!is_null($errcode)) {
			$this->setErrCode($errcode);
		}
	}


	public function reset()
	{
		$this->_arrMsg = array(
			'code'    => null,	// integer, 错误码
			"message" => null	// string, 错误描述
		);
	}
	

	/**
	 * 设置错误码
	 *
	 * @param integer $errCode 投之家协议定义的全局错误码
	 * @return null
	 */
	public function setErrCode($errCode)
	{
		$this->_arrMsg['code']    = intval($errCode);
		$this->_arrMsg['message'] = Touzhijia_Platform_Protocol_ErrorCode::getErrMsg(intval($errCode));
	}

}
