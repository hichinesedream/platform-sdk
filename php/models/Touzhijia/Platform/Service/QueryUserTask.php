<?php

/**
 * 查询用户信息功能处理类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Service
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-31 11:16:46
 */
class Touzhijia_Platform_Service_QueryUserTask implements Touzhijia_Platform_Service_BaseTask
{
	// @var Touzhijia_Platform_Entity_QueryUserReq
	private $_req;

	public function __construct($req)
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
	 * 查询用户信息
	 *
	 * @return Touzhijia_Platform_Entity_QueryUserRes|Touzhijia_Platform_Entity_ErrRes
	 */
	public function doTask()
	{
		// 代码示例项目注: 请将此处代码替换成实际生产数据 begin
		$ret = $this->validate();
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			return new Touzhijia_Platform_Entity_ErrRes($ret);
		}

		$res = new Touzhijia_Platform_Entity_QueryUserRes();

		if ($this->_req->hasIndexItem()) {
			list($field, $arrVals) = $this->_req->getIndex();
			switch ($field) {
				case 'username':
					$rows = $this->fetchUserInfoByNameList($arrVals);
					break;
				default:
					$rows = null;
					break;
			}
		} else {
			list($st, $et) = $this->_req->getTimeRange();
			$rows = $this->fetchUserInfoByTimeRange($st, $et);
		}

		if (is_array($rows)) {
			foreach ($rows as $row) {
				$res->addUserInfo($row);
			}
		}
		// 代码示例项目注: 请将此处代码替换成实际生产数据 end

		return $res;
	}

	private function isUserNameExists($username)
	{
		return true;
	}

	private function fetchUserInfoByTimeRange($st, $et)
	{
		$iRecordCnt = rand(0, 20);
	
		$rows = array();
		for ($i = 0; $i < $iRecordCnt; $i++) {
			$rows[] = $this->genRandomUerInfo(null, 'random generate by fetchUserInfoByTimeRange()');
		}

		return $rows;
	}

	private function fetchUserInfoByNameList($arrUserNameList)
	{
		// 遍历每一个username，获取详细信息返回
		$rows = array();
		foreach ($arrUserNameList as $username) {
			if ($this->isUserNameExists($username)) {
				$rows[] = $this->genRandomUerInfo($username, 'random generate by fetchUserInfoByNameList()');
			}
		}

		return $rows;
	}

	// 随机产生一条数据用于测试, 实际开发需要从DB中提取
	private function genRandomUerInfo($username = null, $debug_msg = null)
	{
		$row = new Touzhijia_Platform_Entity_UserInfo();

		$rnd = rand(0, 90000);
		$username = is_null($username) ? "test_user_$rnd" : $username;
		$row->setUserName($username);
		$row->setUserNamep("test_user_at_plat_$rnd");
		$row->setRegisterAt(date('Y-m-d H:i:s'));
		$row->setBindAt(date('Y-m-d H:i:s'));
		$row->setBindType(rand(0, 1));

		$awaitAmount   = rand(0, 5000000) / 100;
		$balanceAmount = rand(0, 500000) / 100;
		$totalAmount   = $awaitAmount + $balanceAmount;
		$row->setAssets($awaitAmount, $balanceAmount, $totalAmount);

		$row->setTags(array($debug_msg));

		return $row;
	}
}	
