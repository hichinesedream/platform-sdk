<?php

class Touzhijia_Platform_Entity_CreateUserReq
{
	const SERVICE_NAME = 'createUser';
	
	private $_arrReq;
	
	public function __construct()
	{
		$this->clear();
	}
	
	public function clear()
	{
		$this->_arrReq = array(
			'service' => strtolower(self::SERVICE_NAME),
			'body'    => null
		);
	}
	
	public function setUserName($v)
	{
		$this->_arrReq['body']['username'] = $v;
	}
	
	public function setTelephone($v)
	{
		$this->_arrReq['body']['telephone'] = $v;
	}
	
	public function setEmail($v)
	{
		$this->_arrReq['body']['email'] = $v;
	}
	
	public function setIdCard($number, $name)
	{
		$this->_arrReq['body']['idCard']['number'] = $number;
		$this->_arrReq['body']['idCard']['name']   = $name;
	}
	
	public function setBankCard($number, $bank, $branch)
	{
		$this->_arrReq['body']['bankCard']['number'] = $number;
		$this->_arrReq['body']['bankCard']['bank']   = $bank;
		$this->_arrReq['body']['bankCard']['branch'] = $branch;
	}
	
	public function parseFromJson($strJson)
	{
		$arrMsg = json_decode($strJson, true);
		if ($arrMsg == false) {
			return false;
		}

		// 检查必选字段是否存在
		$arrNeedFields = array_keys($this->_arrReq);
		foreach ($arrNeedFields as $field) {
			if (!array_key_exists($field, $arrMsg)) {
				return false;
			}   
		} 

		// 赋值
		foreach ($arrNeedFields as $field) {
			$this->_arrMsg[$field] = $arrMsg[$field];
		} 
	}
	
	public function toString()
	{
		return json_encode($this->_arrReq);
	}
}
