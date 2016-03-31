<?php

/**
 * 消息体基类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:42:04
 */

class Touzhijia_Platform_Entity_BaseMsg
{
	/**
	 * @var array $_arrMsg 用数组来表达消息体
	 */
	protected $_arrMsg;


	public function __construct()
	{
		$this->reset();
	}


	/**
	 * 重置消息体(清空)
	 *
	 * 该函数必须要派生类进行重写, 否则抛出异常
	 *
	 * @return null
	 */
	public function reset()
	{
		$error = 'This method [' . __METHOD__ . '] must be overrided in your inheritance class ';
		throw new Exception($error);
	}


	/**
	 * 根据json串对消息体进行赋值
	 *
	 * @see Touzhijia_Platform_Protocol_ErrorCode
	 * @param string $strJson json string
	 * @return integer 是否成功
	 */
	public function fromJson($strJson)
	{
		$arrMsg = json_decode($strJson, true);
		if ($arrMsg == false) {
			return Touzhijia_Platform_Protocol_ErrorCode::PARSE_JSON_ERROR;
		}

		// 检查必选字段是否存在
		if (false == Touzhijia_Platform_Util_Array::isAllKeyExistsInArray($this->_arrMsg, $arrMsg)) {
			return Touzhijia_Platform_Protocol_ErrorCode::PARSE_JSON_ERROR;
		}

		// 赋值
		$this->_arrMsg = $arrMsg;
		return Touzhijia_Platform_Protocol_ErrorCode::OK;
	}


	/**
	 * 将消息体从array格式转化为json格式
	 *
	 * @return string json string
	 */
	public function toJson()
	{
		return json_encode($this->_arrMsg);
	}

}
