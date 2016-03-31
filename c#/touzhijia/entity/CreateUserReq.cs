using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class CreateUserReq
    {
        // 投之家用户名
        public String username{ get; set; }

        // 手机号码
        public String telephone{ get; set; }

        // 电子邮箱
        public String email{ get; set; }

        public class IdCard
        {
            // 身份证号
            public String number{ get; set; }
            // 实名
            public String name{ get; set; }

        }

        public class BankCard
        {
            public String number{ get; set; }
            public String bankName{ get; set; }
            public String branch{ get; set; }
        }

        // 身份证信息
        public IdCard idCard{ get; set; }

        // 银行卡信息
        public BankCard bankCard{ get; set; }
    }
}
