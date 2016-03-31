<?php

/**
 * 错误请求包定义(调试时使用)
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:54:29
 */
class Touzhijia_Platform_Entity_ErrReq extends Touzhijia_Platform_Entity_BaseMsg
{
	const SERVICE_NAME = 'not_exists_service';
	
	public function reset()
	{
		$this->_arrMsg = array(
				'service' => self::SERVICE_NAME,
				'body'    => null
				);
	}
}
