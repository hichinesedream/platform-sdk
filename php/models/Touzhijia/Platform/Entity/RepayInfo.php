<?php

/**
 * 回款记录结构体定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:06:49
 */
class Touzhijia_Platform_Entity_RepayInfo
{
	// 回款类型定义
	const REPAY_TYPE_NORMAL   = 0;		// 正常到期得到回款
	const REPAY_TYPE_TRANSFER = 1;		// 债权转让得到回款

	private $_arrMsg;

	public function __construct()
	{
		$this->reset();
	}

	public function reset()
	{
		$this->_arrMsg = array(
				'id'       => null,	// string, 回款订单ID, 全局唯一
				'investid' => null,	// string, 该笔回款对应的投资订单ID
				'bid'      => null,	// string, 标的ID
				'username' => null,	// string, 用户在合作平台的用户名
				'amount'   => null,	// float, 回款金额
				'income'   => null,	// float, 回款收益
				'repayAt'  => null,	// datetime, 回款时间
				'type'     => null,	// enum, 回款类型, 取值请参考协议文档
				'tags'     => null	// array, 标签, 用来扩充记录属性
				);
	}

	public function setId($v)
	{
		$this->_arrMsg['id'] = strval($v);
		return true;
	}

	public function setInvestId($v)
	{
		$this->_arrMsg['investid'] = strval($v);
		return true;
	}

	public function setBid($v)
	{
		$this->_arrMsg['bid'] = strval($v);
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

	public function setRate($v)
	{
		$this->_arrMsg['rate'] = floatval($v);
		return true;
	}

	public function setIncome($v)
	{
		$this->_arrMsg['income'] = floatval($v);
		return true;
	}
	
	public function setRepayAt($t)
	{
		$this->_arrMsg['repayAt'] = date('Y-m-d H:i:s', strtotime($t));
		return true;
	}
	
	public function setType($v)
	{
		$isValid = true;
		switch ($v) {
			case self::REPAY_TYPE_NORMAL:		// 正常到期得到回款
			case self::REPAY_TYPE_TRANSFER:		// 债权转让得到回款
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
