<?php

class Touzhijia_Platform_Entity_CreateUserReq extends Touzhijia_Platform_Entity_BaseMsg
{
	const SERVICE_NAME = 'createUser';
	
	public function reset()
	{
		$this->_arrMsg = array(
				'service' => strtolower(self::SERVICE_NAME),
				'body'    => array(
					'username'  => null,		// string, 投之家用户名
					'telephone' => null,		// string, 手机
					'email'     => null,		// string, 邮箱
					'idCard'    => array(
						'number' => null, 	// string, 身份证号码
						'name'   => null	// string, 身份证姓名
						),
					'bankCard'  => array(
						'number' => null,	// string, 银行卡卡号
						'bank'   => null,	// string, 银行名称
						'branch' => null	// string, 银行卡开户行支行
						)
					)
				);
	}
	
	public function setUserName($v)
	{
		$this->_arrMsg['body']['username'] = $v;
	}
	
	public function setTelephone($v)
	{
		$this->_arrMsg['body']['telephone'] = $v;
	}
		
	public function setEmail($v)
	{
		$this->_arrMsg['body']['email'] = $v;
	}
	
	public function setIdCard($number, $name)
	{
		$this->_arrMsg['body']['idCard']['number'] = $number;
		$this->_arrMsg['body']['idCard']['name']   = $name;
	}
	
	public function setBankCard($number, $bank, $branch)
	{
		$this->_arrMsg['body']['bankCard']['number'] = $number;
		$this->_arrMsg['body']['bankCard']['bank']   = $bank;
		$this->_arrMsg['body']['bankCard']['branch'] = $branch;
	}
	
}
