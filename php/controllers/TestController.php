<?php

/**
 * 测试代码，通过调用该controller下的代码，模拟投之家向合作平台的 /tzjservice 单一入口发出请求
 */
class TestController
{
	// 实现协议接口的serivce url
	private $_service_url;

	// 实现加密器
	private $_crypter;
	
	public function __construct()
	{
		header("Content-type: text/html; charset=utf-8");

		// 本代码示例项目的service url位于与本controller平级的controller
		$this->_service_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/tzjservice';

		// 根据 ./config/defines.php 中定义的配置，创建加密器
		$this->_crypter = new Touzhijia_Platform_Protocol_MsgCrypter(
				TZJ_PROTOCOL_TOKEN,
				TZJ_PROTOCOL_AES_KEY,
				TZJ_PROTOCOL_PLAT_ID);
	}
	
	
	public function indexAction()
	{
		echo '测试代码，通过调用该controller下的代码，模拟投之家向合作平台的 /tzjservice 单一入口发出请求';
	}
	

	// 模拟请求：创建新用户
	public function createuserAction()
	{
		$req = new Touzhijia_Platform_Entity_CreateUserReq();
		$req->setUserName('longsky');
		$req->setTelephone('18912345678');
		$req->setEmail('test@qq.com');
		$req->setIdCard('450000198701015566', '张三');
		$req->setBankCard('6226096511223344', '招商银行', '深圳分行高新支行');
		$this->doRequest($req);
	}
	

	// 模拟请求：绑定老用户
	public function binduserAction()
	{
		$req = new Touzhijia_Platform_Entity_BindUserReq();
		$req->setUserName('longsky');
		$req->setTelephone('18912345678');
		$req->setEmail('test@qq.com');
		$req->setIdCard('450000198701015566', '张三');
		$req->setBankCard('6226096511223344', '招商银行', '深圳分行高新支行');
		$this->doRequest($req);
	}


	private function showError($msg)
	{
		echo $msg;
	}

	private function doRequest($req)
	{
		// 打包
		list($ret, $strPostData) = $this->_crypter->encrypt($req->toString());
		if ($ret != 0) {
			$this->showError("Build request data failed. ret_code = $ret");
			die();
		}

		// 请求
		$strJson = Touzhijia_Platform_Util_Http::doPost($this->_service_url, $strPostData);
		list($ret, $data) = $this->_crypter->decrypt($strJson);
		if ($ret != 0) {
			$this->showError("Build request data failed. ret_code = $ret");
			die();
		}

		// 打印结果
		Touzhijia_Platform_Util_String::print_rr($data);
	}
}
