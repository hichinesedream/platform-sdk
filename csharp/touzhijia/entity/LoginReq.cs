using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class LoginReq
    {
        // 投之家用户名
        public String username{ get; set; }
        // 平台用户名
        public String usernamep{ get; set; }
        // 标的ID
        public String bid{ get; set; }
        // 登录类型：0:PC, 1:WAP
        public int type{ get; set; }
    }
}
