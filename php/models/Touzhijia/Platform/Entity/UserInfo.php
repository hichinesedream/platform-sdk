<?php

/**
 * 用户详情结构体定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:06:17
 */
class Touzhijia_Platform_Entity_UserInfo
{
	private $_arrMsg;

	public function __construct()
	{   
		$this->reset();
	} 

	public function reset()
	{
		$this->_arrMsg = array(
				'username'   => null,			// string, 投之家用户名
				'usernamep'  => null,			// string, 合作平台用户名
				'registerAt' => null,			// datetime, 用户在合作平台的注册时间, Unit:秒
				'bindAt'     => null,			// datetime, 用户绑定投之家的时间(当前时间), Unit:秒
				'bindType'   => null,			// enum, 0:投之家带来的新用户, 1:平台已有的老用户
				'assets'     => array(
					'awaitAmount'   => null,	// float, 待收余额
					'balanceAmount' => null,	// float, 帐户余额
					'totalAmount'   => null,	// float, 资产总额 (=待收余额+帐户余额)
					),
				'tags'       => null			// array, 标签
				);
	}
	
	public function setUserName($v)
	{
		$this->_arrMsg['username'] = $v;
		return true;
	}
	
	public function setUserNamep($v)
	{
		$this->_arrMsg['usernamep'] = $v;
		return true;
	}
	
	public function setRegisterAt($v)
	{
		$this->_arrMsg['registerAt'] = date('Y-m-d H:i:s', strtotime($v));
		return true;
	}
	
	public function setBindAt($v)
	{
		$this->_arrMsg['bindAt'] = date('Y-m-d H:i:s', strtotime($v));
		return true;
	}
	
	public function setBindType($v)
	{
		if (($v !== 0) && ($v !== 1)) {
			return false;
		}

		$this->_arrMsg['bindType'] = $v;
		return true;
	}
	
	public function setAssets($awaitAmount, $balanceAmount, $totalAmount)
	{
		$this->_arrMsg['assets']['awaitAmount']   = floatval($awaitAmount);
		$this->_arrMsg['assets']['balanceAmount'] = floatval($balanceAmount);
		$this->_arrMsg['assets']['totalAmount']   = floatval($totalAmount);
		return true;
	}
	
	public function setTags($v)
	{
		if (!is_array($v)) {
			return false;
		}

		$this->_arrMsg['tags'] = $v;
		return true;
	}

	public function getAll()
	{
		return $this->_arrMsg;
	}
	
}
