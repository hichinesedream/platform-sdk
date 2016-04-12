<?php

/**
 * 查询回款记录请求包定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:53:59
 */
class Touzhijia_Platform_Entity_QueryRepaysReq extends Touzhijia_Platform_Entity_BaseMsg
{
	const SERVICE_NAME = 'queryRepays';

	public function reset()
	{
		$this->_arrMsg = array(
				'service' => self::SERVICE_NAME,
				'body'    => array(
					"timeRange" => array(
						"startTime" => null,	// datetime, 开始时间
						"endTime" => null	// datetime, 结束时间
						),
					"index" => array(
						"name" => null,		// string, 查询字段，本版本固定为：id|investId|username
						"vals" => null		// array, 查询内容，与name字段形成 WHERE $name IN implode(',', $vals) 的条件
						),
					)
				);
	}

	public function hasIndexItem()
	{
		if (isset($this->_arrMsg['body']['index']['name']) && isset($this->_arrMsg['body']['index']['vals'])) {
			return true;
		}

		return false;
	}
	
	public function setTimeRange($st, $et)
	{
		if (strtotime($st) > strtotime($et)) {
			return false;
		}

		$this->_arrMsg['body']['timeRange']['startTime'] = date('Y-m-d H:i:s', strtotime($st));
		$this->_arrMsg['body']['timeRange']['endTime']   = date('Y-m-d H:i:s', strtotime($et));
		return true;
	}
	
	public function setIndex($strField, $arrVals)
	{
		if (!is_string($strField) || !is_array($arrVals)) {
			return false;
		}

		$this->_arrMsg['body']['index']['name'] = $strField;
		$this->_arrMsg['body']['index']['vals'] = $arrVals;
		$this->_isIndexSet = true;

		return true;
	}
		
	
	public function getTimeRange()
	{
		return array(
				$this->_arrMsg['body']['timeRange']['startTime'],
				$this->_arrMsg['body']['timeRange']['endTime']
			    );
	}
	
	public function getIndex()
	{
		return array(
				$this->_arrMsg['body']['index']['name'],
				$this->_arrMsg['body']['index']['vals']
			    );
	}
	
}
