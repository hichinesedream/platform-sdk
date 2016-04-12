<?php

/**
 * 标的详情结构体定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:49:04
 */
class Touzhijia_Platform_Entity_BidInfo
{
	// 标的类型定义
	const BID_TYPE_COMMON     = 0;		// 普通标
	const BID_TYPE_TRANSFER   = 1;		// 转让标
	const BID_TYPE_NET_WORTH  = 2;		// 净值标
	const BID_TYPE_CURRENT    = 10;		// 活期产品
	const BID_TYPE_BEGINER    = 101;	// 新手标
	const BID_TYPE_EXPERIENCE = 102;	// 体验标
	const BID_TYPE_OTHER      = 100;	// 其他标


	// 标的状态定义
	CONST BID_STATUS_LENDING          = 0;	// 还款中
	CONST BID_STATUS_HAS_PAID_OFF     = 1;	// 已还清
	CONST BID_STATUS_EXPIRED_TO_PAY   = 2;	// 逾期
	CONST BID_STATUS_RAISE_PROCESSING = 3;	// 投标中
	CONST BID_STATUS_RASIE_NOT_ENOUGH = 4;	// 流标
	CONST BID_STATUS_RASIE_CANCEL     = 5;	// 撤标
	CONST BID_STATUS_RAISE_FULL       = 6;	// 满标
	CONST BID_STATUS_HAS_RELEASE      = 7;	// 放款
	CONST BID_STATUS_OTHER            = 99;	// 其他


	/**
	 * @var array 消息体
	 */
	private $_arrMsg;

	public function __construct()
	{
		$this->reset();
	}

	public function reset()
	{
		$this->_arrMsg = array(
				'id'              => null,	// string, 标的ID
				'url'             => null,	// string, 标的URL
				'title'           => null,	// string, 标的标题
				'desc'            => null,	// string, 标的描述
				'borrower'        => null,	// string, 借款人
				'borrowAmount'    => null,	// float, 借款金额
				'remainAmount'    => null,	// float, 剩余金额
				'minInvestAmount' => null,	// float, 起投金额
				'period'          => null,	// string, 借款期限, 7天标识为7d, 1个月表示为1m, 活期为0
				'originalRate'    => null,	// float, 原始年化利率, 13.5表示13.5%
				'rewardRate'      => null,	// float, 奖励利率, 13.5表示13.5%
				'status'          => null,	// enum, 标的状态, 取值见协议定义
				'repayment'       => null,	// string, 还款方式, 如: 按月还息到期还本
				'type'            => null,	// enum, 标的类型, 取值见协议定义
				'prop'            => null,	// string, 标的属性, 如: 车贷、房贷、信用贷等
				'createAt'        => null,	// datetime, 创建时间
				'publishAt'       => null,	// datetime, 起投时间
				'closeAt'         => null,	// datetime, 截止时间
				'fullAt'          => null,	// datetime, 满标时间
				'repayDate'       => null,	// date, 还款日期, 如果分多期归还, 取最后一期回款日期
				'tags'            => null	// array, 标签, 用来扩充标的属性
				);
	}

	public function setId($v)
	{
		$this->_arrMsg['id'] = strval($v);
		return true;
	}

	public function setUrl($v)
	{
		$this->_arrMsg['url'] = strval($v);
		return true;
	}

	public function setTitle($v)
	{
		$this->_arrMsg['title'] = strval($v);
		return true;
	}

	public function setDesc($v)
	{
		$this->_arrMsg['desc'] = strval($v);
		return true;
	}

	public function setBorrower($v)
	{
		$this->_arrMsg['borrower'] = strval($v);
		return true;
	}

	public function setBorrowAmount($v)
	{
		$this->_arrMsg['borrowAmount'] = floatval($v);
		return true;
	}

	public function setRemainAmount($v)
	{
		$this->_arrMsg['remainAmount'] = floatval($v);
		return true;
	}

	public function setMinInvestAmount($v)
	{
		$this->_arrMsg['minInvestAmount'] = floatval($v);
		return true;
	}

	// 设置借款期限, 7天标识为7d, 1个月表示为1m, 活期为0
	public function setPeriod($v)
	{
		$v = strtolower(strval($v));

		// 活期没有期限, 设置成0
		if ($v == "0" || $v == "0d" || $v == "0m") {
			$this->_arrMsg['period'] = "0";
			return true;
		}

		$len = strlen($v);
		if ($len < 2) {
			return false;
		}

		// 单位不是指定的 d|m 两个字母
		$periodUnit = $v{$len - 1};
		if ($periodUnit != "d" && $periodUnit != "m") {
			return false;
		}

		// 期限取值不是数字
		$periodNum = substr($v, 0, $len - 1);
		if (!is_numeric($periodNum)) {
			return false;
		}

		// 取值合法, 设置成功
		$this->_arrMsg['period'] = $v;
		return true;
	}

	// 设置原始年化利率, 13.5表示13.5%
	public function setOriginalRate($v)
	{
		$this->_arrMsg['originalRate'] = floatval($v);
		return true;
	}

	// 设置奖励利率, 13.5表示13.5%
	public function setRewardRate($v)
	{
		$this->_arrMsg['rewardRate'] = floatval($v);
		return true;
	}

	// 设置标的状态, 取值见协议定义
	public function setStatus($v)
	{
		$isValid = true;
		switch ($v) {
			case self::BID_STATUS_LENDING:		// 还款中
			case self::BID_STATUS_HAS_PAID_OFF:	// 已还清
			case self::BID_STATUS_EXPIRED_TO_PAY:	// 逾期
			case self::BID_STATUS_RAISE_PROCESSING:	// 投标中
			case self::BID_STATUS_RASIE_NOT_ENOUGH:	// 流标
			case self::BID_STATUS_RASIE_CANCEL:	// 撤标
				$this->_arrMsg['status'] = $v;
				$isValid = true;
				break;
			default:
				// 取值不合法, 返回false
				$isValid = false;
				break;
		}

		return $isValid;

	}

	public function setRepayment($v)
	{
		$this->_arrMsg['repayment'] = strval($v);
		return true;
	}
	
	public function setType($v)
	{
		$isValid = true;
		switch ($v) {
			case self::BID_TYPE_COMMON:	// 普通标
			case self::BID_TYPE_TRANSFER:	// 转让标
			case self::BID_TYPE_NET_WORTH:	// 净值标
			case self::BID_TYPE_CURRENT:	// 活期产品
			case self::BID_TYPE_BEGINER:	// 新手标
			case self::BID_TYPE_EXPERIENCE:	// 体验标
			case self::BID_TYPE_OTHER:	// 其他标
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
	
	public function setProp($v)
	{
		$this->_arrMsg['prop'] = strval($v);
		return true;
	}
	
	public function setCreateAt($t)
	{
		$this->_arrMsg['createAt'] = date('Y-m-d H:i:s', strtotime($t));
		return true;
	}
	
	public function setPublishAt($t)
	{
		$this->_arrMsg['publishAt'] = date('Y-m-d H:i:s', strtotime($t));
		return true;
	}
	
	public function setCloseAt($t)
	{
		$this->_arrMsg['closeAt'] = date('Y-m-d H:i:s', strtotime($t));
		return true;
	}
	
	public function setFullAt($t)
	{
		$this->_arrMsg['fullAt'] = date('Y-m-d H:i:s', strtotime($t));
		return true;
	}
	
	public function setRepayDate($d)
	{
		$this->_arrMsg['repayDate'] = date('Y-m-d', strtotime($d));
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
