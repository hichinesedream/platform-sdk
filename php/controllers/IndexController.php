<?php

class IndexController
{
	public function indexAction()
	{
		header("Content-type: text/html; charset=utf-8");
		
		$url = trim(CUR_URL, "/") . '/tzjservice';		
		echo "投之家垂直导流接口demo，demo代码的单一入口URL为：<a href=\"$url\">$url/&lt;service_name&gt;</a>";
	}
}
