using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class QueryReq
    {
        public class TimeRange
        {
            // 开始时间
            public DateTime startTime { get; set; }
            // 结束时间
            public DateTime endTime { get; set; }

        }

        public class Index
        {
            // 索引名字
            public String name { get; set; }
            // 索引查询
            public List<String> vals { get; set; }

        }

        public TimeRange timeRange { get; set; }

        public Index index { get; set; }

    }
}
