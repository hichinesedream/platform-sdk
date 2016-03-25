package com.touzhijia.platform.service;

import java.util.List;

import com.touzhijia.platform.entity.BidInfo;
import com.touzhijia.platform.entity.CreateUserReq;
import com.touzhijia.platform.entity.InvestInfo;
import com.touzhijia.platform.entity.LoginReq;
import com.touzhijia.platform.entity.QueryReq;
import com.touzhijia.platform.entity.Redirect;
import com.touzhijia.platform.entity.RepayInfo;
import com.touzhijia.platform.entity.UserInfo;


/**
 * Created by architect of touzhijia on 2016/3/1.
 */
public interface PlatformService {
	// 创建新用户
	public UserInfo createUser(CreateUserReq req) throws PlatformException;
	
	// 绑定老用户，需要跳转到用户授权界面
	public Redirect bindUser(CreateUserReq req) throws PlatformException;
	
	// 登录，设置登录态并跳转到平台
	public Redirect login(LoginReq req) throws PlatformException;
	
	// 查询用户信息
	public List<UserInfo> queryUser(QueryReq req) throws PlatformException;
	
	// 查询标的信息
	public List<BidInfo> queryBids(QueryReq req) throws PlatformException;
	
	// 查询投资记录
	public List<InvestInfo> queryInvests(QueryReq req) throws PlatformException;
	
	// 查询回款记录
	public List<RepayInfo> queryRepays(QueryReq req) throws PlatformException;
}
