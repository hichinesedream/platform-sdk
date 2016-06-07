using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class BidInfo
    {
        public String id{ get; set; }
        public String url{ get; set; }
        public String title{ get; set; }
        public String desc{ get; set; }
        public String borrower{ get; set; }
        public float borrowAmount{ get; set; }
        public float remainAmount{ get; set; }
        public float minInvestAmount{ get; set; }
        public String period { get; set; }
        public float originalRate { get; set; }
        public float rewardRate { get; set; }
        public int status{ get; set; }
        public int repayment{ get; set; }
        public int type{ get; set; }
        public DateTime createAt{ get; set; }
        public DateTime publishAt{ get; set; }
        public DateTime closeAt{ get; set; }
        public DateTime fullAt{ get; set; }
        public DateTime repayDate { get; set; }
        public List<String> tags{ get; set; }
    }
}
