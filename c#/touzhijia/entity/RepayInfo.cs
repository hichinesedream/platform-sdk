using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class RepayInfo
    {
        // 订单ID
        public String id;
        // 投资订单ID
        public String investId;
        // 投资者投之家用户名
        public String username;
        // 回款金额
        public float amount;
        // 回款利率
        public float rate;
        // 回款收益
        public float income;
        // 回款类型：0:普通回款，1:转让回款
        public int type;
        // 回款时间
        public DateTime repayAt;
        // 标签
        public List<String> tags;

    }
}
