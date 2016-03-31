using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.entity
{
    public class ServiceData
    {
        public ServiceData()
        {
        }
        public ServiceData(string service, string body)
        {
            this.service = service;
            this.body = body;
        }

        public string service { get; set; }
        public string body { get; set; }
    }
}
