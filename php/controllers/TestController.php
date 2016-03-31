<?php

/**
 * 测试控制器, 模拟向合作平台的 /tzjservice 单一入口发出请求, 用于调试协议
 * 
 * @category   Touzhijia
 * @package    null
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 14:42:32
 */
class TestController
{
	/**
	 * @var string 实现协议接口的serivce url
	 */
	private $_service_url;


	/**
	 * @var Touzhijia_Platform_Protocol_MsgCrypter 加解密器
	 */
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
		echo "<!DOCTYPE html>\n<html>\n<head>\n";
		echo "<title>投之家-搜索导流接入协议-调试页</title>\n";
		echo "</head>\n<body>\n";
		echo "<p>测试代码，模拟向合作平台的单一入口 <a href=\"{$this->_service_url}\">{$this->_service_url}</a> 发出请求</p>\n";
		echo "<h2>Call samples：</h2>\n";
		echo "<ol>\n";
		echo "<li><a href='".CUR_URL."/createuser'>创建新用户</a></li>\n";
		echo "<li><a href='".CUR_URL."/binduser'>绑定老户</a></li>\n";
		echo "<li><a href='".CUR_URL."/login'>自动登陆</a></li>\n";
		echo "<li><a href='".CUR_URL."/queryuser'>查询用户信息</a></li>\n";
		echo "<li><a href='".CUR_URL."/querybids'>查询标的信息</a></li>\n";
		echo "<li><a href='".CUR_URL."/queryinvests'>查询投资记录</a></li>\n";
		echo "<li><a href='".CUR_URL."/queryrepays'>查询回款记录</a></li>\n";
		echo "<li><a href='".CUR_URL."/noservice'>故意发个错误请求</a></li>\n";
		echo "</ol>\n<br><br>\n";
		echo "<p>copyright(c) touzhijia.com</p>\n";
		echo "</body>\n</html>\n";
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
	

	// 模拟请求：自动登陆
	public function loginAction()
	{
		$req = new Touzhijia_Platform_Entity_LoginReq();

		// TestCase 1: 登录并跳转到合作平台首页
		$req->reset();
		$req->setUserName('longsky');
		$req->setUserNamep('tzjlongsky');
		$req->setBid(Touzhijia_Platform_Entity_LoginReq::LOGIN_TO_HOMEPAGE);
		$req->setType(Touzhijia_Platform_Entity_LoginReq::LOGIN_DEVICE_TYPE_PC);
		$this->doRequest($req);

		// TestCase 2: 登录并跳转到合作平台个人中心
		$req->reset();
		$req->setUserName('longsky');
		$req->setUserNamep('tzjlongsky');
		$req->setBid(Touzhijia_Platform_Entity_LoginReq::LOGIN_TO_USER_CENTER);
		$req->setType(Touzhijia_Platform_Entity_LoginReq::LOGIN_DEVICE_TYPE_WAP);
		$this->doRequest($req);

		// TestCase 3: 登录并跳转到合作平台标的详情页
		$req->reset();
		$req->setUserName('longsky');
		$req->setUserNamep('tzjlongsky');
		$req->setBid("51144");
		$req->setType(Touzhijia_Platform_Entity_LoginReq::LOGIN_DEVICE_TYPE_WAP);
		$this->doRequest($req);
	}
	

	// 模拟请求：查询用户信息
	public function queryuserAction()
	{
		$req = new Touzhijia_Platform_Entity_QueryUserReq();

		// TestCase 1: 按时间区间查询
		$req->reset();
		$st = date('Y-m-d H:i:s', strtotime('1 days ago'));
		$et = date('Y-m-d H:i:s');
		$req->setTimeRange($st, $et);
		$this->doRequest($req);

		// TestCase 2: 按用户名列表查询
		$req->reset();
		$req->setIndex('username', array('user1', 'user2', 'user3'));
		$this->doRequest($req);
	}
	

	// 模拟请求：查询标的信息
	public function querybidsAction()
	{
		$req = new Touzhijia_Platform_Entity_QueryBidsReq();

		// TestCase 1: 按时间区间查询, 设置查询区间超长
		$req->reset();
		$st = date('Y-m-d H:i:s', strtotime('5 days ago'));
		$et = date('Y-m-d H:i:s');
		$req->setTimeRange($st, $et);
		$this->doRequest($req);

		// TestCase 2: 按标的ID列表查询
		$req->reset();
		for ($i = 1; $i <= 25; $i++) {
			$bidArray[] = "bid$i";
		}
		$req->setIndex('id', $bidArray);
		$this->doRequest($req);

		// TestCase 3: 按时间区间查询
		$req->reset();
		$st = date('Y-m-d H:i:s', strtotime('1 days ago'));
		$et = date('Y-m-d H:i:s');
		$req->setTimeRange($st, $et);
		$this->doRequest($req);

		// TestCase 4: 按标的ID列表查询
		$req->reset();
		$req->setIndex('id', array('bid0001', 'bid0002', 'bid0003'));
		$this->doRequest($req);
	}
	

	// 模拟请求：查询投资记录
	public function queryinvestsAction()
	{
		$req = new Touzhijia_Platform_Entity_QueryInvestsReq();

		// TestCase 1: 按时间区间查询
		$req->reset();
		$st = date('Y-m-d H:i:s', strtotime('1 days ago'));
		$et = date('Y-m-d H:i:s');
		$req->setTimeRange($st, $et);
		$this->doRequest($req);

		// TestCase 2: 按标的ID列表查询
		$req->reset();
		$req->setIndex('bid', array('bid0001', 'bid0002', 'bid0003'));
		$this->doRequest($req);

		// TestCase 3: 按用户名列表查询, 由于按用户查询流水会很大, 必须指定查询时间范围
		$req->reset();
		$st = date('Y-m-d H:i:s', strtotime('1 days ago'));
		$et = date('Y-m-d H:i:s');
		$req->setTimeRange($st, $et);
		$req->setIndex('username', array('user1', 'user2', 'user3'));
		$this->doRequest($req);
	}
	

	// 模拟请求：查询回款记录
	public function queryrepaysAction()
	{
		$req = new Touzhijia_Platform_Entity_QueryRepaysReq();

		// TestCase 1: 按时间区间查询
		$req->reset();
		$st = date('Y-m-d H:i:s', strtotime('1 days ago'));
		$et = date('Y-m-d H:i:s');
		$req->setTimeRange($st, $et);
		$this->doRequest($req);

		// TestCase 2: 按投资记录ID列表查询
		$req->reset();
		$req->setIndex('investid', array('repay_id0001', 'repay_id0002', 'repay_id0003'));
		$this->doRequest($req);

		// TestCase 3: 按标的ID列表查询
		$req->reset();
		$req->setIndex('bid', array('bid0001', 'bid0002', 'bid0003'));
		$this->doRequest($req);

		// TestCase 4: 按用户名列表查询, 由于按用户查询流水会很大, 必须指定查询时间范围
		$req->reset();
		$st = date('Y-m-d H:i:s', strtotime('1 days ago'));
		$et = date('Y-m-d H:i:s');
		$req->setTimeRange($st, $et);
		$req->setIndex('username', array('user1', 'user2', 'user3'));
		$this->doRequest($req);
	}


	// 模拟请求：传递一个错误的service请求
	public function noserviceAction()
	{
		$req = new Touzhijia_Platform_Entity_ErrReq();
		$this->doRequest($req);
	}


	/**
	 * 将请求按照投之家协议组包, 向服务端发起请求, 按协议格式解开响应包, 打印到浏览器上
	 *
	 * @param Touzhijia_Platform_Entity_BaseMsg $req 请求包对象
	 * @return null
	 */
	private function doRequest($req)
	{
		// 打包
		list($ret, $postData) = $this->_crypter->encrypt($req->toJson());
		if ($ret != 0) {
			$this->showError($ret);
		}

		// 投之家向合作平台发起请求
		list($httpCode, $jsonString) = Touzhijia_Platform_Util_Http::doPost($this->_service_url, $postData);
		echo "<hr>请求包为：<br>\n";
		echo $req->toJson(). "<br><br>\n";

		// 处理结果
		if ($httpCode >= 400) {
			echo "服务器返回${httpCode}，接收到的数据包为：<br>\n";
			echo $jsonString;
		} else {
			list($ret, $data) = $this->_crypter->decrypt($jsonString);
			if ($ret != 0) {
				$jsonString = str_replace("<script>", "<!--<script>", $jsonString);
				$jsonString = str_replace("</script>", "</script>-->", $jsonString);
				$moreMsg = "解密失败(可能是一个跳转请求)，接收到的数据包为：<br>\n" . $jsonString . "<br>\n";
				$this->showError($ret, $moreMsg);
			}

			// 打印结果
			echo "解密成功，接收到的数据包为：<br>\n";
			$arrData = json_decode($data, true);
			Touzhijia_Platform_Util_Array::print_rr($arrData);
		}
	}


	/**
	 * 向浏览器抛出500错误
	 *
	 * @param integer $errCode 协议约定的全局错误码
	 * @param string  $moreMsg 需要额外打印的错误信息
	 * @return null
	 */
	private function showError($errCode, $moreMsg = null)
	{
		header('HTTP/1.1 500 Internal Server Error');
		header("Content-type: text/html; charset=utf-8");
		$stRes = new Touzhijia_Platform_Entity_ErrRes($errCode);
		echo $stRes->toJson();

		if (!empty($moreMsg)) {
			echo "<br>\n";
			echo $moreMsg;
		}

		die();
	}
}
