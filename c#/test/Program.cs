using System;
using System.Collections.Generic;
using System.Reflection;
using System.Runtime.Serialization;
using System.Text;
using touzhijia;
using touzhijia.entity;
using touzhijia.service;
using touzhijia.util;

namespace test
{
    class Program
    {
        [DataContract]
        class userinfo
        {
            [DataMember]
            public string username { get; set; }
            [DataMember]
            public string telephone { get; set; }
            [DataMember]
            public string email { get; set; }
            [DataMember]
            public idCard idCard { get; set; }
            [DataMember]
            public bankCard bankCard { get; set; }
        }
        [DataContract]
        class idCard
        {
            [DataMember]
            public string name { get; set; }
            [DataMember]
            public string number { get; set; }

        }
        [DataContract]
        class bankCard
        {
            [DataMember]
            public string name { get; set; }
            [DataMember]
            public string bank { get; set; }
            [DataMember]
            public string branch { get; set; }

        }
        static void Main(string[] args)
        {
            //testCrypt();
            string token = "shitoujinrong";
            string platID = "stjr";
            string encodingAESKey = "123456788765432112345678";
            string nonce = "1234";
            Platform platform = new Platform(token, encodingAESKey, platID, nonce);
            var user = new userinfo();
            user.username = "longsky";
            user.telephone = "18912345678";
            user.email = "test @qq.com";
            idCard idcard = new idCard();
            idcard.name = "张三";
            idcard.number = "450000198701015566";
            bankCard bankcard = new bankCard();
            bankcard.name = "603123456342323212";
            bankcard.bank = "招商银行";
            bankcard.branch = "深圳分行高新支行";
            user.idCard = idcard;
            user.bankCard = bankcard;
            var sd = new ServiceData();
            sd.service = "createUser";
            sd.body = Json.Encode<userinfo>(user);
            var msg = new Message();
            msg.timestamp = (DateTime.Now.ToUniversalTime().Ticks - 621355968000000000) / 10000000;
            msg.nonce = nonce;
            var mcrypt = new MessageCrypt(token, encodingAESKey, platID);
            mcrypt.EncryptMsg(sd, ref msg);
            Message nmsg = platform.Command(msg, null);
            Console.WriteLine("msg: {0}", Json.Encode<Message>(nmsg));
            Console.WriteLine("Enter 请继续");
            Console.ReadLine();
        }

        private static void testCrypt()
        {
            string token = "shitoujinrong";
            string platID = "stjr";
            string encodingAESKey = "123456788765432112345678";
            string nonce = "1234";
            var mcrypt = new MessageCrypt(token, encodingAESKey, platID);
            var sd = new ServiceData();
            sd.service = "createUser";
            // sd.body = "touzhijia";
            sd.body = "{\"username\":\"longsky\",\"telephone\":\"18912345678\",\"email\":\"test @qq.com\",\"idCard\":{\"number\":\"450000198701015566\",\"name\":\"张三\"},\"bankCard\":{\"name\":\"\",\"bank\":\"招商银行\",\"branch\":\"深圳分行高新支行\"}}";
            var msg = new Message();
            msg.timestamp = 1457316109;
            msg.nonce = nonce;
            int res = mcrypt.EncryptMsg(sd, ref msg);
            if (res != 0)
            {
                Console.WriteLine(res);
            }
            else
            {
                Console.WriteLine(msg.signature);
                Console.WriteLine(msg.data);
                Console.WriteLine(msg.timestamp);
            }
            Console.WriteLine("开始解密");

            var nsd = new ServiceData();
            int result = mcrypt.DecryptMsg(msg, ref nsd);
            if (result != 0)
            {
                Console.WriteLine(result);
            }
            else
            {
                Console.WriteLine(nsd.body);
                Console.WriteLine(nsd.service);
            }
            Console.WriteLine("Enter 请继续");
            Console.ReadLine();
        }
    }
}
