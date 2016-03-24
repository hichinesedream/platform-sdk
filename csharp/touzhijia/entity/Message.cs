using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class Message
    {
        public string data { get; set; }
        public long timestamp { get; set; }
        public string nonce { get; set; }
        public string signature { get; set; }
    }
}
