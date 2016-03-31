<?php


class Touzhijia_Platform_Service_CreateUserTask implements Touzhijia_Platform_Service_BaseTask
{
	// @var Touzhijia_Platform_Entity_CreateUserReq
	private $_req;

	public function __construct(Touzhijia_Platform_Entity_CreateUserReq $req)
	{
		$this->_req = $req;
	}


	public function validate()
	{
		if ($this->isTelephoneExists($this->_req->getTelephone())) {
			return new Touzhijia_Platform_Entity_ErrRes(Touzhijia_Platform_Protocol_ErrorCode::TELEPHONE_HAVE_USED);
		}

		if ($this->isEmailExists($this->_req->getEmail())) {
			return new Touzhijia_Platform_Entity_ErrRes(Touzhijia_Platform_Protocol_ErrorCode::EMAIL_HAVE_USED);
		}

		list($idcard_number, $idcard_name) = $this->_req->getIdCard();
		if ($this->isIdCardExists($idcard_number)) {
			return new Touzhijia_Platform_Entity_ErrRes(Touzhijia_Platform_Protocol_ErrorCode::IDCARD_HAVE_USED);
		}

		return Touzhijia_Platform_Protocol_ErrorCode::OK;
	}


	/**
	 * 创建新用户
	 *
	 * @return Touzhijia_Platform_Entity_CreateUserRes|Touzhijia_Platform_Entity_ErrRes
	 */
	public function doTask()
	{
		// 代码示例项目注: 请将此处代码替换成实际生产数据 begin

		// 设置随机出现接口故障，测试调用方的容错机制, 线上代码请将此处随机故障逻辑移除
		$rand_num = mt_rand(0, 5);
		if ($rand_num == 0) {
			return new Touzhijia_Platform_Entity_ErrRes(Touzhijia_Platform_Protocol_ErrorCode::APPLICATION_ERROR);
		}

		// 检查参数合法性
		$ret = $this->validate();
		if ($ret != Touzhijia_Platform_Protocol_ErrorCode::OK) {
			return new Touzhijia_Platform_Entity_ErrRes($ret);
		}

		// ......
		// 创建一个新用户
		// ......

		$data['username']   = $this->_req->getUserName();
		$data['usernamep']  = $this->generateNewUserName($this->_req->getTelephone());
		$data['registerAt'] = date('Y-m-d H:i:s', time());
		$data['bindAt']     = date('Y-m-d H:i:s', time());
		$data['bindType']   = 0;
		$data['tags']       = array("加息标", "车辆抵押", "支持代金券");
		// 代码示例项目注: 请将此处代码替换成实际生产数据 end

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

	private function isTelephoneExists($telephone)
	{
		return false;
	}

	private function isEmailExists($email)
	{
		return false;
	}

	private function isIdCardExists($idcard_number)
	{
		return false;
	}

	private function generateNewUserName($telephone)
	{
		// 根据手机号作为合作平台的用户名，要检查用户名唯一性
		return $telephone;
	}

}	
