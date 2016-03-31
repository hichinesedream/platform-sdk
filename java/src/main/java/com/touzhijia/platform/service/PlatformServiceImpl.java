package com.touzhijia.platform.service;

import java.util.List;

import org.springframework.stereotype.Service;

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
@Service
public class PlatformServiceImpl implements PlatformService {

	@Override
	public UserInfo createUser(CreateUserReq req) throws PlatformException {
		// TODO Auto-generated method stub
		return null;
	}
	
	@Override
	public Redirect bindUser(CreateUserReq req) throws PlatformException {
		// TODO Auto-generated method stub
		return null;
	}


	@Override
	public Redirect login(LoginReq req) throws PlatformException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<UserInfo> queryUser(QueryReq req) throws PlatformException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<BidInfo> queryBids(QueryReq req) throws PlatformException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<InvestInfo> queryInvests(QueryReq req) throws PlatformException {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public List<RepayInfo> queryRepays(QueryReq req) throws PlatformException {
		// TODO Auto-generated method stub
		return null;
	}

}
