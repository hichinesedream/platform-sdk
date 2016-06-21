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

        IList<BidInfo> PlatformService.queryBids(QueryReq req)
        {
            //依据req 去查询数据库
            IList<BidInfo> list = new List<BidInfo>();
            BidInfo info = new BidInfo();
            info.title = "test_bid1";
            info.id = "12345";
            info.originalRate = 12;
            info.rewardRate = 1;
            info.desc = "test_bid2";
            list.Add(info);
            return list;
        }

        IList<InvestInfo> PlatformService.queryInvests(QueryReq req)
        {
            return null;
        }

        IList<RepayInfo> PlatformService.queryRepays(QueryReq req)
        {
            return null;
        }

        IList<UserInfo> PlatformService.queryUser(QueryReq req)
        {
            return null;
        }
    }
}
