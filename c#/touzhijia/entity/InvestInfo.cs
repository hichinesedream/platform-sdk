using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class InvestInfo
    {
        public String id;
        public String bid;
        public String burl;
        public String username;
        public float amount;
        public float actualAmount;
        public float income;
        public DateTime investAt;
        public DateTime repayAt;
        public List<String> tags;
    }
}
