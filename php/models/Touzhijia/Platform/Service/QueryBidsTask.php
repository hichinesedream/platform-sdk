<?php

/**
 * 查询标的信息功能处理类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Service
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-31 11:17:05
 */
class Touzhijia_Platform_Service_QueryBidsTask implements Touzhijia_Platform_Service_BaseTask
{
	// @var Touzhijia_Platform_Entity_QueryBidsReq
	private $_req;

	public function __construct(Touzhijia_Platform_Entity_QueryBidsReq $req)
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
	 * 查询标的信息
	 *
	 * @return Touzhijia_Platform_Entity_QueryBidsRes|Touzhijia_Platform_Entity_ErrRes
	 */
	public function doTask()
	{
		// 代码示例项目注: 请将此处代码替换成实际生产数据 begin
		$ret = $this->validate();
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			return new Touzhijia_Platform_Entity_ErrRes($ret);
		}

		$res = new Touzhijia_Platform_Entity_QueryBidsRes();

		if ($this->_req->hasIndexItem()) {
			list($field, $arrVals) = $this->_req->getIndex();
			switch ($field) {
				case 'id':
					$rows = $this->fetchBidInfoByIdList($arrVals);
					break;
				default:
					$rows = null;
			}
		} else {
			list($st, $et) = $this->_req->getTimeRange();
			$rows = $this->fetchBidInfoByTimeRange($st, $et);
		}

		if (is_array($rows)) {
			foreach ($rows as $row) {
				$res->addBidInfo($row);
			}
		}
		// 代码示例项目注: 请将此处代码替换成实际生产数据 end

		return $res;
	}

	private function isIdExists($bid)
	{
		return true;
	}

	private function fetchBidInfoByTimeRange($st, $et)
	{
		$iRecordCnt = rand(0, 20);
	
		$rows = array();
		for ($i = 0; $i < $iRecordCnt; $i++) {
			$rows[] = $this->genRandomBidInfo(null, "random generate by fetchBidInfoByTimeRange()");
		}

		return $rows;
	}

	private function fetchBidInfoByIdList($arrIdList)
	{
		// 遍历每一个id, 获取详细信息返回
		$rows = array();
		foreach ($arrIdList as $id) {
			if ($this->isIdExists($id)) {
				$rows[] = $this->genRandomBidInfo($id, 'random generate by fetchBidInfoByIdList()');
			}
		}

		return $rows;
	}

	// 随机产生一条数据用于测试, 实际开发需要从DB中提取
	private function genRandomBidInfo($id = null, $debug_msg = null)
	{
		$row = new Touzhijia_Platform_Entity_BidInfo();

		$rnd = rand(0, 900000);
		$id = is_null($id) ? "test_bid_$rnd" : $id;
		$row->setId($id);
		$row->setUrl("test_url_$rnd");
		$row->setTitle("test_title_$rnd");
		$row->setDesc("test_desc_$rnd");
		$row->setBorrower("test_borrower_$rnd");

		$amount = rand(0, 100000000) / 100;
		$row->setBorrowAmount($amount);
		$row->setRemainAmount(1000000 - $amount);
		$row->setMinInvestAmount(100.00);
		$row->setMinInvestAmount(100.00);
		$row->setPeriod('1.5m');
		$row->setOriginalRate('13.5');
		$row->setRewardRate('1.2');
		$row->setStatus(Touzhijia_Platform_Entity_BidInfo::BID_STATUS_RAISE_PROCESSING);
		$row->setRepayment('按月付息到期还本');
		$row->setType(Touzhijia_Platform_Entity_BidInfo::BID_TYPE_TRANSFER);
		$row->setProp('红本抵押');
		$row->setCreateAt(date('Y-m-d H:i:s', strtotime('1 days ago')));
		$row->setPublishAt(date('Y-m-d H:i:s', strtotime('28 minutes ago')));
		$row->setCloseAt(date('Y-m-d H:i:s', strtotime('1 hours')));
		$row->setFullAt(date('Y-m-d H:i:s', strtotime('8 minutes ago')));
		$row->setRepayDate(date('Y-m-d H:i:s', strtotime('30 days')));
		
		// 其他标签, 这里示例用调试信息代替, 线上环境请填写有意义的信息
		$row->setTags(array($debug_msg));

		return $row;
	}
}	
