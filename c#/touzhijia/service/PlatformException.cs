using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace touzhijia.service
{
    public class PlatformException : Exception
    {
        public int code { get; set; }

        public PlatformException(int code, String message):base(message)
        {
            this.code = code;
        }

    }
}
