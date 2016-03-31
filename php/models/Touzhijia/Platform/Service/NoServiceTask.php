<?php


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
