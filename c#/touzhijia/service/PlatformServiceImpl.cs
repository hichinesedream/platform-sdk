using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using touzhijia.entity;

namespace touzhijia.service
{
    public class PlatformServiceImpl : PlatformService
    {
        Redirect PlatformService.bindUser(CreateUserReq req)
        {
            return null;
        }

        UserInfo PlatformService.createUser(CreateUserReq req)
        {
            Console.WriteLine("create");
            UserInfo userinfo = new UserInfo();
            userinfo.username = req.username;
            userinfo.usernamep = "tzj_"+ req.username;
            userinfo.bindType = 0;
            userinfo.registerAt = DateTime.Now;
            userinfo.bindAt = DateTime.Now;
            return userinfo;
        }

        Redirect PlatformService.login(LoginReq req)
        {
            return null;
        }

        BidInfo[] PlatformService.queryBids(QueryReq req)
        {
            return null;
        }

        InvestInfo[] PlatformService.queryInvests(QueryReq req)
        {
            return null;
        }

        RepayInfo[] PlatformService.queryRepays(QueryReq req)
        {
            return null;
        }

        UserInfo[] PlatformService.queryUser(QueryReq req)
        {
            return null;
        }
    }
}
