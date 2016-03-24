<?php

class TzjserviceController
{
	// 统一入口函数
	public function indexAction()
	{
		// 获取POST数据
		$strPostRawData = file_get_contents('php://input');

		// 解密数据包
		$crypter = new Touzhijia_Platform_Protocol_MsgCrypter(TZJ_PROTOCOL_TOKEN, TZJ_PROTOCOL_AES_KEY, TZJ_PROTOCOL_PLAT_ID);
		list($ret, $data) = $crypter->decrypt($strPostRawData);
		if ($ret != 0) {
			$this->showError($ret);
		}

		// 从解密后的数据包中提取service参数
		$arrReq = json_decode($data, true);
		if (empty($arrReq) || !isset($arrReq['service'])) {
			$this->showError(Touzhijia_Platform_Protocol_ErrorCode::MISSING_SERVICE_NAME);
		}

		// 根据service参数进行请求分发
		$chain = new Touzhijia_Platform_Util_CmdChain();
		$chain->setAction('createuser',   __CLASS__, 'createuser');
		$chain->setAction('binduser',     __CLASS__, 'binduser');
		$chain->setAction('login',        __CLASS__, 'login');
		$chain->setAction('queryuser',    __CLASS__, 'queryuser');
		$chain->setAction('querybids',    __CLASS__, 'querybids');
		$chain->setAction('queryinvests', __CLASS__, 'queryinvests');
		$chain->setAction('queryrepays',  __CLASS__, 'queryrepays');
		$chain->setDefaultAction(__CLASS__, 'noservice');

		// 获取返回
		$stRes = $chain->dispatch($arrReq['service'], $arrReq);

		// 如果需要返回错误, 不需要加密, 直接json返回
		if ($stRes instanceof Touzhijia_Platform_Entity_ErrRes) {
			header('HTTP/1.1 500 Internal Server Error');
			header("Content-type: application/json; charset=utf-8");
			die($stRes->toJson());
		}

		// 将返回结果以json格式表示, 加密, 并按协议格式打包
		list($ret, $strEncryptedData) = $crypter->encrypt($stRes->toJson());
		if ($ret != 0) {
			$this->showError(Touzhijia_Platform_Protocol_ErrorCode::GEN_RETURN_MSG_ERROR);
		}

		header("Content-type: application/json; charset=utf-8");
		echo $strEncryptedData;
	}

	
	
	public function createuser($arrReq)
	{
		return Touzhijia_Platform_Service_CreateUserTask::doTask($arrReq);
	}
	
	public function binduser($arrReq)
	{
		echo __METHOD__;
		Touzhijia_Platform_Util_String::print_rr($arrReq);
	}
	
	public function login($arrReq)
	{
		echo __METHOD__;
		Touzhijia_Platform_Util_String::print_rr($arrReq);
	}
	
	public function queryuser($arrReq)
	{
		echo __METHOD__;
		Touzhijia_Platform_Util_String::print_rr($arrReq);
	}
	
	public function querybids($arrReq)
	{
		echo __METHOD__;
		Touzhijia_Platform_Util_String::print_rr($arrReq);
	}
	
	public function queryinvests($arrReq)
	{
		echo __METHOD__;
		Touzhijia_Platform_Util_String::print_rr($arrReq);
	}
	
	public function queryrepays($arrReq)
	{
		echo __METHOD__;
		Touzhijia_Platform_Util_String::print_rr($arrReq);
	}


	public function noservice($arrReq)
	{
		return Touzhijia_Platform_Service_NoServiceTask::doTask($arrReq);
	}
	
	
	public function showError($errcode)
	{
		header('HTTP/1.1 500 Internal Server Error');
		header("Content-type: application/json; charset=utf-8");
		$stRes = new Touzhijia_Platform_Entity_ErrRes($errcode);
		die($stRes->toJson());
	}	
}
