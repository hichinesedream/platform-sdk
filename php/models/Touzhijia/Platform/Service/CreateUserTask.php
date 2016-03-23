<?php

class Touzhijia_Platform_Service_CreateUserTask
{
	static public function doTask($arrReq)
	{
		// process
		// ...

		$res = new Touzhijia_Platform_Entity_CreateUserRes();
		$res->setUserName('test_username');
		$res->setUserNamep('test_usernamep');
		$res->setRegisterAt(date('Y-m-d H:i:s'));
		$res->setBindAt(date('Y-m-d H:i:s'));
		$res->setBindType(0);
		$res->setTags("加息标|车辆抵押|支持代金券");
		return $res;
	}
}	
