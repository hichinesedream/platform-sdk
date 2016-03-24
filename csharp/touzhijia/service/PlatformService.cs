using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using touzhijia.entity;

namespace touzhijia.service
{
    public interface PlatformService
    {
        // 创建新用户
        UserInfo createUser(CreateUserReq req);

        // 绑定老用户，需要跳转到用户授权界面
        Redirect bindUser(CreateUserReq req);

        // 登录，设置登录态并跳转到平台
        Redirect login(LoginReq req);

        // 查询用户信息
        UserInfo[] queryUser(QueryReq req);

        // 查询标的信息
        BidInfo[] queryBids(QueryReq req);

        // 查询投资记录
        InvestInfo[] queryInvests(QueryReq req);

        // 查询回款记录
        RepayInfo[] queryRepays(QueryReq req);
    }
}
