using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;

namespace touzhijia.entity
{
    [Serializable]
    public class ServiceData
    {
        public ServiceData()
        {
        }
        public ServiceData(string service, Object body)
        {
            this.service = service;
            this.body = body;
        }

        public string service { get; set; }
        public Object body { get; set; }
    }
}
