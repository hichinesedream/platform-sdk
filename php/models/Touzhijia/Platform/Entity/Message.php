<?php

class Touzhijia_Platform_Entity_Message extends Touzhijia_Platform_Entity_BaseMsg
{
	public function reset()
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

}
