<?php

class Touzhijia_Platform_Entity_Message
{
	private $_arrMsg;
	
	public function __construct()
	{
		$this->clear();
	}
	
	public function clear()
	{
		$this->_arrMsg = array(
			'data'      => null,
			'timestamp' => null,
			'nonce'     => null,
			"signature" => null
		);
	}
	
	public function setData($v)
	{
		$this->_arrMsg['data'] = $v;
	}
	
	public function setTimestamp($v)
	{
		$this->_arrMsg['timestamp'] = $v;
	}
	
	public function setNonce($v)
	{
		$this->_arrMsg['nonce'] = $v;
	}
	
	public function setSignature($v)
	{
		$this->_arrMsg['signature'] = $v;
	}
		
	public function getData()
	{
		return $this->_arrMsg['data'];
	}
	
	public function getTimestamp()
	{
		return $this->_arrMsg['timestamp'];
	}
	
	public function getNonce()
	{
		return $this->_arrMsg['nonce'];
	}	
	
	public function getSignature()
	{
		return $this->_arrMsg['signature'];
	}

	public function parseFromJson($strJson)
	{
		$arrMsg = json_decode($strJson, true);
		if ($arrMsg == false) {
			return false;
		}

		// 检查必选字段是否存在
		$arrNeedFields = array_keys($this->_arrMsg);
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
		return json_encode($this->_arrMsg);
	}
}
