<?php

/**
 * 重定向响应包定义
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Entity
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:07:37
 */
class Touzhijia_Platform_Entity_RedirectRes
{
	private $_redirectUrl = null;

	public function __construct($redirectUrl = null)
	{
		$this->setRedirectUrl($redirectUrl);
	}


	/**
	 * 设置重定向url
	 *
	 * @param string $redirectUrl
	 * @return null
	 */
	public function setRedirectUrl($redirectUrl)
	{
		$this->_redirectUrl = $redirectUrl;
	}


	/**
	 * 获取重定向url
	 *
	 * @return null
	 */
	public function getRedirectUrl()
	{
		return $this->_redirectUrl;
	}

}
