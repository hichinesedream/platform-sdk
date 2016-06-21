using Newtonsoft.Json;
using Newtonsoft.Json.Converters;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;

namespace touzhijia.util
{
    public class Json
    {
       
        public static string Encode<Type>(Type t)
        {
            return JsonConvert.SerializeObject(t, new TzjDateTimeConvertor());
        }
        public static Type Decode<Type>(string data)
        {
            return JsonConvert.DeserializeObject<Type>(data, new TzjDateTimeConvertor());
        }

    }
    public class TzjDateTimeConvertor : DateTimeConverterBase
    {
        public override object ReadJson(JsonReader reader, Type objectType, object existingValue, JsonSerializer serializer)
        {
            return DateTime.Parse(reader.Value.ToString());
        }

        public override void WriteJson(JsonWriter writer, object value, JsonSerializer serializer)
        {
            writer.WriteValue(((DateTime)value).ToString("yyyy-MM-dd HH:mm:ss"));
        }
    }
}
