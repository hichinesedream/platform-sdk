<?php


class Touzhijia_Platform_Service_CreateUserTask
{
	static public function doTask($arrReq)
	{
		// 代码示例项目注: 请将此处代码替换成实际生产数据 begin
		$rand_num = mt_rand(0, 5);
		if ($rand_num == 0) {
			$res = new Touzhijia_Platform_Entity_ErrRes();
			$res->setErrCode(Touzhijia_Platform_Protocol_ErrorCode::TEST_ERROR);
			return $res;	
		}

		$data['username']   = 'test_username';
		$data['usernamep']  = 'test_username_at_plat';
		$data['registerAt'] = date('Y-m-d H:i:s', time());
		$data['bindAt']     = date('Y-m-d H:i:s', time());
		$data['bindType']   = 0;
		$data['tags']       = array("加息标", "车辆抵押", "支持代金券");
		// 代码示例项目注: 请将此处代码替换成实际生产数据 end

		// ...
		// ...
		// ...

		// 正常返回
		$res = new Touzhijia_Platform_Entity_CreateUserRes();
		$res->setUserName($data['username']);
		$res->setUserNamep($data['usernamep']);
		$res->setRegisterAt($data['registerAt']);
		$res->setBindAt($data['bindAt']);
		$res->setBindType($data['bindType']);
		$res->setTags($data['tags']);
		return $res;
	}
}	
