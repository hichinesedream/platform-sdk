<?php

/**
 * 查询回款记录功能处理类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Service
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-31 11:17:38
 */
class Touzhijia_Platform_Service_QueryRepaysTask implements Touzhijia_Platform_Service_BaseTask
{
	// @var Touzhijia_Platform_Entity_QueryRepaysReq
	private $_req;

	public function __construct(Touzhijia_Platform_Entity_QueryRepaysReq $req)
	{
		$this->_req = $req;
	}

	public function validate()
	{   
		// 查询时间区间过长可能导致处理超时, 需要做出限制
		list($st, $et) = $this->_req->getTimeRange();
		if (!empty($st)) {
			$stSec = strtotime($st);
			$etSec = strtotime($et);

			// 起始时间不能大于终止时间
			if ($stSec > $etSec) {
				return Touzhijia_Platform_Protocol_ErrorCode::START_GREAT_THAN_END;
			}

			// 查询区间限制在2天内
			if (($etSec - $stSec) > (86400*2+1)) {
				return Touzhijia_Platform_Protocol_ErrorCode::TIME_RANGE_EXCEED;
			}
		}

		// 查询项数量过多可能导致处理超时, 需要做出限制
		if ($this->_req->hasIndexItem()) {
			list($field, $arrVals) = $this->_req->getIndex();
			if (count($arrVals) > 20) {
				return Touzhijia_Platform_Protocol_ErrorCode::QUERY_ITEM_COUNT_EXCEED;
			}
		}

		return Touzhijia_Platform_Protocol_ErrorCode::OK;
	} 

	/**
	 * 查询投资记录
	 *
	 * @return Touzhijia_Platform_Entity_QueryRepaysRes|Touzhijia_Platform_Entity_ErrRes
	 */
	public function doTask()
	{
		// 代码示例项目注: 请将此处代码替换成实际生产数据 begin
		$ret = $this->validate();
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			return new Touzhijia_Platform_Entity_ErrRes($ret);
		}   

		$res = new Touzhijia_Platform_Entity_QueryRepaysRes();

		if ($this->_req->hasIndexItem()) {
			list($field, $arrVals) = $this->_req->getIndex();
			switch ($field) {
				case 'id':
					$rows = $this->fetchRepayInfoByIdList($arrVals);
					break;
				case 'bid':
					$rows = $this->fetchRepayInfoByBidList($arrVals);
					break;
				case 'username':
					list($st, $et) = $this->_req->getTimeRange();
					$rows = $this->fetchRepayInfoByUserNameList($arrVals, $st, $et);
					break;
				default:
					$rows = null;
			}
		} else {
			list($st, $et) = $this->_req->getTimeRange();
			$rows = $this->fetchRepayInfoByTimeRange($st, $et);
		}

		if (is_array($rows)) {
			foreach ($rows as $row) {
				$res->addRepayInfo($row);
			}
		}
		// 代码示例项目注: 请将此处代码替换成实际生产数据 end

		return $res;
	}

	private function isIdExists($id)
	{
		return true;
	}

	private function isBidExists($bid)
	{
		return true;
	}

	private function isUserNameExists($username)
	{
		return true;
	}

	private function fetchRepayInfoByTimeRange($st, $et)
	{
		$iRecordCnt= rand(0, 200);
	
		$rows = array();
		for ($i = 0; $i < $iRecordCnt; $i++) {
			$rows[] = $this->genRandomRepayInfo(null, "random generate by fetchRepayInfoByTimeRange()");
		}

		return $rows;
	}

	private function fetchRepayInfoByIdList($arrIdList)
	{
		// 遍历每一个id(invest_id)，获取详细信息返回
		$rows = array();
		foreach ($arrIdList as $id) {
			if ($this->isIdExists($id)) {
				$rows[] = $this->genRandomRepayInfo($id, "random generate by fetchBidInfoByIdList()");
			}
		}

		return $rows;
	}

	private function fetchRepayInfoByBidList($arrBidList)
	{
		// 遍历每一个bid，获取详细信息返回
		$rows = array();
		foreach ($arrBidList as $bid) {
			if ($this->isBidExists($bid)) {
				$rows[] = $this->genRandomRepayInfo($bid, "random generate by fetchRepayInfoByBidList()");
			}
		}

		return $rows;
	}

	private function fetchRepayInfoByUserNameList($arrUserNameList, $st, $et)
	{
		// 遍历每一个username，获取详细信息返回
		$rows = array();
		foreach ($arrUserNameList as $username) {
			if ($this->isUserNameExists($username)) {
				$rows[] = $this->genRandomRepayInfo($username, "random generate by fetchRepayInfoByUserNameList()");
			}
		}

		return $rows;
	}

	// 随机产生一条数据用于测试, 实际开发需要从DB中提取
	private function genRandomRepayInfo($id = null, $debug_msg = null)
	{
		$row = new Touzhijia_Platform_Entity_RepayInfo();

		$rnd = rand(0, 900000);
		$id = is_null($id) ? "test_id_$rnd" : $id;
		$row->setId($id);
		$row->setInvestId("test_investid_$rnd");
		$row->setBid("test_bid_$rnd");
		$row->setUserName("test_username_$rnd");
		$row->setAmount(rand(0, 100000)/100);
		$row->setIncome(rand(0, 10000)/100);
		$row->setRepayAt(date('Y-m-d H:i:s'));
		$row->setType(Touzhijia_Platform_Entity_RepayInfo::REPAY_TYPE_NORMAL);
		$row->setTags(array($debug_msg));

		return $row;
	}
}	
