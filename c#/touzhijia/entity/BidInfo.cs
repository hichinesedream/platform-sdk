using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;

namespace touzhijia.entity
{
    [DataContract]
    public class BidInfo
    {
        [DataMember]
        public String id{ get; set; }
        [DataMember]
        public String url{ get; set; }
        [DataMember]
        public String title{ get; set; }
        [DataMember]
        public String desc{ get; set; }
        [DataMember]
        public String borrower{ get; set; }
        [DataMember]
        public float borrowAmount{ get; set; }
        [DataMember]
        public float remainAmount{ get; set; }
        [DataMember]
        public float minInvestAmount{ get; set; }
        [DataMember]
        public String period{ get; set; }
        [DataMember]
        public float originalRate{ get; set; }
        [DataMember]
        public float awardRate{ get; set; }
        [DataMember]
        public int status{ get; set; }
        [DataMember]
        public int repayment{ get; set; }
        [DataMember]
        public int type{ get; set; }
        [DataMember]
        public DateTime createAt{ get; set; }
        [DataMember]
        public DateTime publishAt{ get; set; }
        [DataMember]
        public DateTime closeAt{ get; set; }
        [DataMember]
        public DateTime fullAt{ get; set; }
        [DataMember]
        public DateTime repayDate{ get; set; }
        [DataMember]
        public String[] tags{ get; set; }
    }
}
