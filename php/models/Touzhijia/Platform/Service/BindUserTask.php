<?php

/**
 * 绑定老用户功能处理类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Service
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-31 11:15:41
 */
class Touzhijia_Platform_Service_BindUserTask implements Touzhijia_Platform_Service_BaseTask
{
	// @var Touzhijia_Platform_Entity_BindUserReq
	private $_req;

	public function __construct(Touzhijia_Platform_Entity_BindUserReq $req)
	{
		$this->_req = $req;
	}


	public function validate()
	{
		return Touzhijia_Platform_Protocol_ErrorCode::OK;
	}


	/**
	 * 绑定老用户
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

		// 跳转到合作平台与投之家的专属关联页面
		return new Touzhijia_Platform_Entity_RedirectRes(TZJ_PROTOCOL_BIND_USER_URL);
	}

}	
