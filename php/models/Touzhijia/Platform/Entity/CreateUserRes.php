<?php

class Touzhijia_Platform_Entity_CreateUserRes
{
	private $_arrRes;
	
	public function __construct()
	{
		$this->clear();
	}
	
	public function clear()
	{
		$this->_arrRes = array(
			'username'   => null,
			'usernamep'  => null,
			'registerAt' => null,
			'bindAt'     => null,
			'bindType'   => null,
			'tags'       => null
		);
	}
	
	public function setUserName($v)
	{
		$this->_arrRes['username'] = $v;
	}
	
	public function setUserNamep($v)
	{
		$this->_arrRes['usernamep'] = $v;
	}
	
	public function setRegisterAt($v)
	{
		$this->_arrRes['registerAt'] = $v;
	}
	
	public function setBindAt($v)
	{
		$this->_arrRes['bindAt'] = $v;
	}
	
	public function setBindType($v)
	{
		$this->_arrRes['bindType'] = $v;
	}
	
	public function setTags($v)
	{
		$this->_arrRes['tags'] = $v;
	}
	
	public function toString()
	{
		return json_encode($this->_arrRes);
	}
}
