<?php

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
	
	
	public function setErrCode($v)
	{
		$this->_arrMsg['code']    = intval($v);
		$this->_arrMsg['message'] = Touzhijia_Platform_Protocol_ErrorCode::getErrMsg(intval($v));

		return true;
	}

}
