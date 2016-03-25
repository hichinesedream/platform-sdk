<?php

class Touzhijia_Platform_Entity_ErrReq extends Touzhijia_Platform_Entity_BaseMsg
{
	const SERVICE_NAME = 'not_exists_service';
	
	public function reset()
	{
		$this->_arrMsg = array(
				'service' => strtolower(self::SERVICE_NAME),
				'body'    => null
				);
	}
}
