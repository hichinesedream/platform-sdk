<?php

/**
 * 无效请求处理类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Service
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-31 11:13:38
 */
class Touzhijia_Platform_Service_NoServiceTask implements Touzhijia_Platform_Service_BaseTask
{
	public function validate()
	{
		return true;
	}


	public function doTask()
	{
		return new Touzhijia_Platform_Entity_ErrRes(Touzhijia_Platform_Protocol_ErrorCode::UNKNOWN_SERVICE_ERROR);
	}
}	
