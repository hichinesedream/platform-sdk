<?php

/**
 * 投之家接口协议实现接口类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Service
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-31 11:09:58
 */
interface Touzhijia_Platform_Service_BaseTask
{
	/**
	 * 参数校验
	 *
	 * @return integer 全局错误码
	 */
	public function validate();


	/**
	 * 响应协议
	 * 
	 * @return Touzhijia_Platform_Entity_ErrRes|Touzhijia_Platform_Entity_RedirectRes|...
	 */
	public function doTask();
}
