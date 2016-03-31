using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Runtime.Serialization.Json;
using System.Text;
using System.Text.RegularExpressions;

namespace touzhijia.util
{
    public class Json
    {
        public static string Encode<Type>(Type t)
        {
            DataContractJsonSerializer serializer = new DataContractJsonSerializer(typeof(Type));
            MemoryStream stream = new MemoryStream();
            serializer.WriteObject(stream, t);
            byte[] data = new byte[stream.Length];
            stream.Position = 0;
            stream.Read(data, 0, (int)stream.Length);
            var jsonstring = Encoding.UTF8.GetString(data);
            //替换Json的Date字符串
            string p = @"\\/Date\((\d+)\+\d+\)\\/";
            MatchEvaluator matchEvaluator = new MatchEvaluator(ConvertJsonDateToDateString);
            Regex reg = new Regex(p);
            jsonstring = reg.Replace(jsonstring, matchEvaluator);
            return jsonstring;
        }
        public static Type Decode<Type>(String data)
        {
            //将"yyyy-MM-dd HH:mm:ss"格式的字符串转为"\/Date(1294499956278+0800)\/"格式
            string p = @"\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}";
            MatchEvaluator matchEvaluator = new MatchEvaluator(ConvertDateStringToJsonDate);
            Regex reg = new Regex(p);
            data = reg.Replace(data, matchEvaluator);
            DataContractJsonSerializer serializer = new DataContractJsonSerializer(typeof(Type));
            MemoryStream stream = new MemoryStream(Encoding.UTF8.GetBytes(data));
            return (Type)serializer.ReadObject(stream);
        }

        /// <summary>
        /// 将Json序列化的时间由/Date(1294499956278+0800)转为字符串
        /// </summary>
        private static string ConvertJsonDateToDateString(Match m)
        {
            string result = string.Empty;
            DateTime dt = new DateTime(1970, 1, 1);
            dt = dt.AddMilliseconds(long.Parse(m.Groups[1].Value));
            dt = dt.ToLocalTime();
            result = dt.ToString("yyyy-MM-dd HH:mm:ss");
            return result;
        }

        /// <summary>
        /// 将时间字符串转为Json时间
        /// </summary>
        private static string ConvertDateStringToJsonDate(Match m)
        {
            string result = string.Empty;
            DateTime dt = DateTime.Parse(m.Groups[0].Value);
            dt = dt.ToUniversalTime();
            TimeSpan ts = dt - DateTime.Parse("1970-01-01");
            result = string.Format("\\/Date({0}+0800)\\/", ts.TotalMilliseconds);
            return result;
        }

    }
}
