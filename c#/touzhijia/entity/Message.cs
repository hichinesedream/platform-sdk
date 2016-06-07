using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;

namespace touzhijia.entity
{
    [Serializable]
    public class Message
    {
        public string data { get; set; }
        public long timestamp { get; set; }
        public string nonce { get; set; }
        public string signature { get; set; }
    }
}
