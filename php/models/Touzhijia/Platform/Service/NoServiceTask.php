<?php


class Touzhijia_Platform_Service_NoServiceTask
{
	static public function doTask($arrReq)
	{
		// 正常返回
		$res = new Touzhijia_Platform_Entity_ErrRes(Touzhijia_Platform_Protocol_ErrorCode::UNKNOWN_SERVICE_ERROR);
		return $res;
	}
}	
