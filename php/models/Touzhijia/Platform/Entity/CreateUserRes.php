<?php

/**
 * 创建新用户响应包定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:56:32
 */
class Touzhijia_Platform_Entity_CreateUserRes extends Touzhijia_Platform_Entity_BaseMsg
{
	const SERVICE_NAME = 'createUser';
	
	public function reset()
	{
		$this->_arrMsg = array(
				'service' => self::SERVICE_NAME,
				'body'    => array(
					'username'   => null,	// string, 投之家用户名
					'usernamep'  => null,	// string, 合作平台用户名
					'registerAt' => null,	// datetime, 用户在合作平台的注册时间, Unit:秒
					'bindAt'     => null,	// datetime, 用户绑定投之家的时间(当前时间), Unit:秒
					'bindType'   => null,	// enum, 0:投之家带来的新用户, 1:平台已有的老用户
					'tags'       => null	// string, 标签
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
	
	public function setRegisterAt($v)
	{
		$this->_arrMsg['body']['registerAt'] = date('Y-m-d H:i:s', strtotime($v));
		return true;
	}
	
	public function setBindAt($v)
	{
		$this->_arrMsg['body']['bindAt'] = date('Y-m-d H:i:s', strtotime($v));
		return true;
	}
	
	public function setBindType($v)
	{
		if (($v !== 0) && ($v !== 1)) {
			return false;
		}

		$this->_arrMsg['body']['bindType'] = $v;
		return true;
	}
	
	public function setTags($v)
	{
		if (!is_array($v)) {
			return false;
		}

		$this->_arrMsg['body']['tags'] = $v;
		return true;
	}
	
}
