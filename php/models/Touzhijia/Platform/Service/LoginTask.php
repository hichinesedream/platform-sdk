<?php


class Touzhijia_Platform_Service_LoginTask implements Touzhijia_Platform_Service_BaseTask
{
	// @var Touzhijia_Platform_Entity_LoginReq
	private $_req;

	public function __construct(Touzhijia_Platform_Entity_LoginReq $req)
	{
		$this->_req = $req;
	}


	public function validate()
	{
		if ($this->isUserBindMatch($this->_req->getUserName(), $this->_req->getUserNamep()) === false) {
			return new Touzhijia_Platform_Entity_ErrRes(Touzhijia_Platform_Protocol_ErrorCode::ERR_USER_BIND_MISMATCH);
		}

		return Touzhijia_Platform_Protocol_ErrorCode::OK;
	}


	/**
	 * 用户自动登录
	 *
	 * @return Touzhijia_Platform_Entity_RedirectRes
	 */
	public function doTask()
	{
		// 代码示例项目注: 请将此处代码替换成实际生产数据 begin

		// 检查参数合法性
		$ret = $this->validate();
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			return new Touzhijia_Platform_Entity_ErrRes($res);
		}

		$url = null;
		switch ($this->_req->getBid()) {
			case 'home':
				$url = TZJ_PROTOCOL_HOMEPAGE_URL;
				break;
			case 'account':
				$url = TZJ_PROTOCOL_USER_CENTER_URL;
				break;
			default:
				$url = TZJ_PROTOCOL_BID_DETAIL_URL . $this->_req->getBid();
				break;
		}

		// 将用户设置为登陆态
		$this->setUserLogin($this->_req->getUserNamep());

		// 返回跳转
		return new Touzhijia_Platform_Entity_RedirectRes($url);
	}

	private function isUserBindMatch($tzjUserName, $platUserName)
	{
		return true;
	}

	private function setUserLogin($platUserName)
	{
		// 通过设置cookie、设置session来将当前用户置为登陆态
		// ...
		// ...
		// ...
		return true;
	}

}	
