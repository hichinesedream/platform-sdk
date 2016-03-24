<?php

class Touzhijia_Platform_Entity_BaseMsg
{
	protected $_arrMsg;
	
	public function __construct()
	{
		$this->reset();
	}
	
	public function reset()
	{
		$error = 'This method [' . __METHOD__ . '] must be overrided in your inheritance class ';
		throw new Exception($error);
	}

	public function parseFromJson($strJson)
	{
		$arrMsg = json_decode($strJson, true);
		if ($arrMsg == false) {
			return false;
		}

		// 检查必选字段是否存在
		if (false == Touzhijia_Platform_Util_Array::isAllKeyExistsInArray($this->_arrMsg, $arrMsg)) {
			return false;
		}

		// 赋值
		$this->_arrMsg = $arrMsg;
	}
	
	public function toJson()
	{
		return json_encode($this->_arrMsg);
	}
}
