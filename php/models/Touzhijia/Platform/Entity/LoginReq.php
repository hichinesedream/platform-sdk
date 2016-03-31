<?php

/**
 * 自动登陆请求包定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:52:34
 */
class Touzhijia_Platform_Entity_LoginReq extends Touzhijia_Platform_Entity_BaseMsg
{
	const SERVICE_NAME = 'login';

	// 标的ID特殊定义
	const LOGIN_TO_HOMEPAGE    = "home";
	const LOGIN_TO_USER_CENTER = "account";

	// 登录来源终端定义
	const LOGIN_DEVICE_TYPE_PC  = 0;
	const LOGIN_DEVICE_TYPE_WAP = 1;
	
	public function reset()
	{
		$this->_arrMsg = array(
				'service' => self::SERVICE_NAME,
				'body'    => array(
					'username'  => null,	// string, 投之家用户名
					'usernamep' => null,	// string, 合作平台用户名
					'bid'       => null,	// string, 标的ID|home|account
					'type'      => null	// enum, 登录来源终端
					)
				);
	}
	
	public function setUserName($v)
	{
		$this->_arrMsg['body']['username'] = $v;
		return true;
	}
	
	public function setUserNamep($v)
	{
		$this->_arrMsg['body']['usernamep'] = $v;
		return true;
	}
		
	public function setBid($v)
	{
		$this->_arrMsg['body']['bid'] = $v;
		return true;
	}
	
	public function setType($v)
	{
		$isValid = true;
		switch ($v) {
			case self::LOGIN_DEVICE_TYPE_PC:
			case self::LOGIN_DEVICE_TYPE_WAP:
				$this->_arrMsg['type'] = $v; 
				$isValid = true;
				break;
			default:
				// 取值不合法, 返回false
				$isValid = false;
				break;
		}   

		return $isValid;
	}

	public function getUserName()
	{
		return $this->_arrMsg['body']['username'];
	}

	public function getUserNamep()
	{
		return $this->_arrMsg['body']['usernamep'];
	}

	public function getBid()
	{
		return $this->_arrMsg['body']['bid'];
	}

	public function getType()
	{
		return $this->_arrMsg['body']['type'];
	}
	
}
