<?php

/**
 * 默认控制器
 * 
 * @category   Touzhijia
 * @package    null
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:36:21
 */
class IndexController
{
	public function indexAction()
	{
		header("Content-type: text/html; charset=utf-8");
		
		$url = trim(CUR_URL, "/") . '/tzjservice';		
		echo "投之家垂直导流接口demo，demo代码的单一入口URL为：<a href=\"$url\">$url/&lt;service_name&gt;</a>";
	}
}
