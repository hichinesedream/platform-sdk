using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;
using System.Text;
using touzhijia.entity;
using touzhijia.service;
using touzhijia.util;
using System.Web;

namespace touzhijia
{
    public class Platform
    {
        private int expire;
        private string nonce;
        private MessageCrypt messageCrypt;
        private PlatformService platformService;

        public Platform(string token, string aesKey, string platID, string nonce = "", int expire = 300)
        {
            messageCrypt = new MessageCrypt(token, aesKey, platID);
            this.nonce = nonce;
            this.expire = expire;
            platformService = new PlatformServiceImpl();
        }
        
        public void SetPlatformServiceImpl(PlatformService platformService)
        {
            this.platformService = platformService;
        }

        public Message Command(Message msg, HttpResponse response)
        {
            if ((DateTime.Now.ToUniversalTime().Ticks - 621355968000000000) / 10000000 - msg.timestamp > expire)
            {
                throw new PlatformException(100, "时间戳过期");
            }
            ServiceData req = new ServiceData();
            var res = messageCrypt.DecryptMsg(msg, ref req);
            if (res != 0)
            {
                throw new PlatformException(res, "解密发生错误");
            }
            Type t = typeof(PlatformService);
            MethodInfo method = null;
            foreach (MethodInfo mi in t.GetMethods())
            {
                if (mi.Name.Equals(req.service))
                {
                    method = mi;
                    break;
                }
            }
            if (method == null)
            {
                throw new PlatformException(101, "错误的业务类型:" + req.service);
            }
            Type pt = method.GetParameters()[0].ParameterType;

            var jsonMethod = typeof(Json).GetMethod("Decode").MakeGenericMethod(pt);
            object obj = jsonMethod.Invoke(typeof(Json), new object[] { req.body });
            object result = null;
            try
            {
                result = method.Invoke(platformService, new object[] { obj });
            }
            catch (TargetException e)
            {
                throw new PlatformException(-1, e.Message);
            }
            catch (TargetInvocationException e)
            {
                throw new PlatformException(-1, e.Message);
            }
            if (result == null)
            {
                throw new PlatformException(-1, "返回的结果为null");
            }
            if (result.GetType() == typeof(Redirect))
            {
                Redirect r = (Redirect)result;
                response.Redirect(r.uri);
            }
            jsonMethod = typeof(Json).GetMethod("Encode").MakeGenericMethod(result.GetType());
            object jsonobj = jsonMethod.Invoke(typeof(Json), new object[] { result });
            ServiceData resp = new ServiceData(req.service, (string)jsonobj);
            Message nmsg = new Message();
            nmsg.timestamp = (DateTime.Now.ToUniversalTime().Ticks - 621355968000000000) / 10000000;
            nmsg.nonce = msg.nonce;
            res = messageCrypt.EncryptMsg(resp, ref nmsg);
            if (res != 0)
            {
                throw new PlatformException(res, "加密发生错误");
            }
            return nmsg;

        }
    }
}
