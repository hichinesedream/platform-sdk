<?php

/**
 * 投资记录结构体定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:05:34
 */
class Touzhijia_Platform_Entity_InvestInfo
{
	private $_arrMsg;

	public function __construct()
	{
		$this->reset();
	}

	public function reset()
	{
		$this->_arrMsg = array(
				'id'            => null,	// string, 投资订单ID, 全局唯一
				'bid'           => null,	// string, 标的ID
				'burl'          => null,	// string, 标的URL
				'username'      => null,	// string, 用户在合作平台的用户名
				'amount'        => null,	// float, 投资金额
				'actualAmount'  => null,	// float, 实际支付的金额
				'income'        => null,	// float, 预期收益
				'investAt'      => null,	// datetime, 投资时间
				'tags'          => null		// array, 标签, 用来扩充记录属性
				);
	}

	public function setId($v)
	{
		$this->_arrMsg['id'] = strval($v);
		return true;
	}

	public function setBid($v)
	{
		$this->_arrMsg['bid'] = strval($v);
		return true;
	}

	public function setBurl($v)
	{
		$this->_arrMsg['burl'] = strval($v);
		return true;
	}

	public function setUserName($v)
	{
		$this->_arrMsg['username'] = strval($v);
		return true;
	}

	public function setAmount($v)
	{
		$this->_arrMsg['amount'] = floatval($v);
		return true;
	}

	public function setActualAmount($v)
	{
		$this->_arrMsg['actualAmount'] = floatval($v);
		return true;
	}

	public function setIncome($v)
	{
		$this->_arrMsg['income'] = floatval($v);
		return true;
	}
	
	public function setInvestAt($t)
	{
		$this->_arrMsg['investAt'] = date('Y-m-d H:i:s', strtotime($t));
		return true;
	}
	
	public function setRepayAt($t)
	{
		$this->_arrMsg['repayAt'] = date('Y-m-d H:i:s', strtotime($t));
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
