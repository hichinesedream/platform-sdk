using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;

namespace touzhijia.entity
{
    [DataContract]
    public class UserInfo
    {

        // 投之家用户名
        [DataMember]
        public String username { get; set; }

        // 平台用户名
        [DataMember]
        public String usernamep { get; set; }

        // 用户在平台的注册时间
        [DataMember]
        public DateTime registerAt { get; set; }

        // 平台用户和投之家用户绑定时间
        [DataMember]
        public DateTime bindAt { get; set; }

        // 绑定类型，0:表示投之家带来的新用户，1:表示平台已有用户
        [DataMember]
        public int bindType { get; set; }

        // 标签
        [DataMember]
        public String[] tags { get; set; }
    }
}
