<?php

/**
 * 投之家协议实现控制器
 * 
 * @category   Touzhijia
 * @package    null
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 15:36:59
 */
class TzjserviceController
{
	// 统一入口函数
	public function indexAction()
	{
		// 获取POST数据
		$postRawString = file_get_contents('php://input');

		// 解密数据包
		$crypter = new Touzhijia_Platform_Protocol_MsgCrypter(TZJ_PROTOCOL_TOKEN, TZJ_PROTOCOL_AES_KEY, TZJ_PROTOCOL_PLAT_ID);
		list($ret, $strReqJson) = $crypter->decrypt($postRawString);
		if ($ret != 0) {
			$this->showError($ret);
			die();
		}

		// 从解密后的数据包中提取service参数
		$arrReq = json_decode($strReqJson, true);
		if (!isset($arrReq['service'])) {
			$this->showError(Touzhijia_Platform_Protocol_ErrorCode::MISSING_SERVICE_NAME);
			die();
		}

		// 根据service参数进行请求分发
		$chain = new Touzhijia_Util_CmdChain();
		$chain->setAction('createUser',   __CLASS__, 'createUser');
		$chain->setAction('bindUser',     __CLASS__, 'bindUser');
		$chain->setAction('login',        __CLASS__, 'login');
		$chain->setAction('queryUser',    __CLASS__, 'queryUser');
		$chain->setAction('queryBids',    __CLASS__, 'queryBids');
		$chain->setAction('queryInvests', __CLASS__, 'queryInvests');
		$chain->setAction('queryRepays',  __CLASS__, 'queryRepays');
		$chain->setDefaultAction(__CLASS__, 'noService');

		// 处理返回结果
		$stRes = $chain->dispatch($arrReq['service'], $strReqJson);
		if ($stRes instanceof Touzhijia_Platform_Entity_ErrRes) {
			// 如果需要返回错误, 不需要加密, 直接json返回
			header('HTTP/1.1 500 Internal Server Error');
			header("Content-type: application/json; charset=utf-8");
			echo $stRes->toJson();

		} else if ($stRes instanceof Touzhijia_Platform_Entity_RedirectRes) {
			// 如果需要执行跳转, 不需要加密, 直接重定向
			header("Location: " . $stRes->getRedirectUrl());

		} else { 
			// 应答返回, 将返回结果以json格式表示, 加密, 并按协议格式打包
			list($ret, $encryptedString) = $crypter->encrypt($stRes->toJson());
			if ($ret != 0) {
				$this->showError(Touzhijia_Platform_Protocol_ErrorCode::GEN_RETURN_MSG_ERROR);
				die();
			} 

			header("Content-type: application/json; charset=utf-8");
			echo $encryptedString;

		}
	}


	// 接口实现：创建新用户
	public function createUser($strReqJson)
	{
		$req  = new Touzhijia_Platform_Entity_CreateUserReq();
		$ret = $req->fromJson($strReqJson);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			$this->showError($ret);
		}

		$task = new Touzhijia_Platform_Service_CreateUserTask($req);
		return $task->doTask();
	}


	// 接口实现：绑定老用户
	public function bindUser($strReqJson)
	{
		$req  = new Touzhijia_Platform_Entity_BindUserReq();
		$ret = $req->fromJson($strReqJson);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			$this->showError($ret);
		}

		$task = new Touzhijia_Platform_Service_BindUserTask($req);
		return $task->doTask();
	}


	// 接口实现：自动登陆
	public function login($strReqJson)
	{
		$req  = new Touzhijia_Platform_Entity_LoginReq();
		$ret = $req->fromJson($strReqJson);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			$this->showError($ret);
		}

		$task = new Touzhijia_Platform_Service_LoginTask($req);
		return $task->doTask();
	}


	// 接口实现：查询用户信息
	public function queryUser($strReqJson)
	{
		$req  = new Touzhijia_Platform_Entity_QueryUserReq();
		$ret = $req->fromJson($strReqJson);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			$this->showError($ret);
		}

		$task = new Touzhijia_Platform_Service_QueryUserTask($req);
		return $task->doTask();
	}


	// 接口实现：查询标的信息
	public function queryBids($strReqJson)
	{
		$req  = new Touzhijia_Platform_Entity_QueryBidsReq();
		$ret = $req->fromJson($strReqJson);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			$this->showError($ret);
		}

		$task = new Touzhijia_Platform_Service_QueryBidsTask($req);
		return $task->doTask();
	}


	// 接口实现：查询投资记录
	public function queryInvests($strReqJson)
	{
		$req  = new Touzhijia_Platform_Entity_QueryInvestsReq();
		$ret = $req->fromJson($strReqJson);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			$this->showError($ret);
		}

		$task = new Touzhijia_Platform_Service_QueryInvestsTask($req);
		return $task->doTask();
	}


	// 接口实现：查询回款记录
	public function queryRepays($strReqJson)
	{
		$req  = new Touzhijia_Platform_Entity_QueryRepaysReq();
		$ret = $req->fromJson($strReqJson);
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			$this->showError($ret);
		}

		$task = new Touzhijia_Platform_Service_QueryRepaysTask($req);
		return $task->doTask();
	}


	// 接口实现：非法请求
	public function noService($strReqJson)
	{
		$task = new Touzhijia_Platform_Service_NoServiceTask();
		return $task->doTask();
	}
	

	/**
	 * 向浏览器抛出500错误
	 *
	 * @param integer $errCode 协议约定的全局错误码
	 * @return null
	 */
	public function showError($errCode)
	{
		header('HTTP/1.1 500 Internal Server Error');
		header("Content-type: application/json; charset=utf-8");
		$stRes = new Touzhijia_Platform_Entity_ErrRes($errCode);
		die($stRes->toJson());
	}	
}
