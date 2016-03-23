<?php

class TzjserviceController
{
	public function __construct()
	{
		header("Content-type: text/html; charset=utf-8");
	}


	// 统一入口函数
	public function indexAction()
	{
		// 获取POST数据
		$strPostRawData = file_get_contents('php://input');

		// 载入加密解密算法器
		$crypter = new Touzhijia_Platform_Protocol_MsgCrypter(TZJ_PROTOCOL_TOKEN, TZJ_PROTOCOL_AES_KEY, TZJ_PROTOCOL_PLAT_ID);

		// 验证合法性
		list($ret, $data) = $crypter->decrypt($strPostRawData);
		if ($ret != 0) {
			$this->showError("解密POST数据失败，错误码：$ret");
			die();
		}

		// 请求分发
		$arrReq = json_decode($data, true);
		if (empty($arrReq) || !isset($arrReq['service'])) {
			$this->showError("解密POST['data']数据失败, 期望 {'service':xx, 'body':xx}, 错误码：$ret");
			die();
		}

		$chain = new Touzhijia_Platform_Util_CmdChain();
		$chain->setAction('createuser',   __CLASS__, 'createuser');
		$chain->setAction('binduser',     __CLASS__, 'binduser');
		$chain->setAction('login',        __CLASS__, 'login');
		$chain->setAction('queryuser',    __CLASS__, 'queryuser');
		$chain->setAction('querybids',    __CLASS__, 'querybids');
		$chain->setAction('queryinvests', __CLASS__, 'queryinvests');
		$chain->setAction('queryrepays',  __CLASS__, 'queryrepays');

		// 获取返回
		$strRes = $chain->dispatch($arrReq['service'], $arrReq);

		// 打包
		list($ret, $data) = $crypter->encrypt($strRes);
		if ($ret != 0) {
			$this->showError("加密POST数据失败，错误码：$ret");
			die();
		}

		// 回吐
		echo $data;
	}

	
	
	public function createuser($arrReq)
	{
		$res = Touzhijia_Platform_Service_CreateUserTask::doTask($arrReq);
		return $res->toString();
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
	
	
	public function showError($msg)
	{
		echo $msg;
	}	
}
