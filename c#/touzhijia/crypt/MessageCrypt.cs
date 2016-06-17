using System;
using System.Collections;
using System.Collections.Generic;
using System.IO;
using System.Security.Cryptography;
using System.Text;
using touzhijia.entity;
using touzhijia.util;

namespace touzhijia
{
    public class MessageCrypt
    {
        string m_sToken;
        string m_sEncodingAESKey;
        string m_sPlatID;
        enum TzjMsgCryptErrorCode
        {
            TzjMsgCrypt_OK = 0,
            TzjMsgCrypt_ValidateSignature_Error = -40001,
            TzjMsgCrypt_ParseXml_Error = -40002,
            TzjMsgCrypt_ComputeSignature_Error = -40003,
            TzjMsgCrypt_IllegalAesKey = -40004,
            TzjMsgCrypt_ValidateAppid_Error = -40005,
            TzjMsgCrypt_EncryptAES_Error = -40006,
            TzjMsgCrypt_DecryptAES_Error = -40007,
            TzjMsgCrypt_IllegalBuffer = -40008,
            TzjMsgCrypt_EncodeBase64_Error = -40009,
            TzjMsgCrypt_DecodeBase64_Error = -40010
        };

        //构造函数
        // @param sToken: 公众平台上，开发者设置的Token
        // @param sEncodingAESKey: 公众平台上，开发者设置的EncodingAESKey
        // @param sAppID: 公众帐号的appid
        public MessageCrypt(string Token, string EncodingAESKey, string PlatID)
        {
            m_sToken = Token;
            m_sPlatID = PlatID;
            m_sEncodingAESKey = EncodingAESKey;
        }



        // 检验消息的真实性，并且获取解密后的明文
        // @param sMsgSignature: 签名串，对应URL参数的msg_signature
        // @param sTimeStamp: 时间戳，对应URL参数的timestamp
        // @param sNonce: 随机串，对应URL参数的nonce
        // @param sPostData: 密文，对应POST请求的数据
        // @param sMsg: 解密后的原文，当return返回0时有效
        // @return: 成功0，失败返回对应的错误码
        public int DecryptMsg(Message msg, ref ServiceData sd)
        {
            //verify signature
            var sMsgSignature = string.Empty;
            int ret = 0;
            ret = VerifySignature(m_sToken, Convert.ToString(msg.timestamp), msg.nonce, msg.data, msg.signature);
            if (ret != 0)
                return ret;
            //decrypt
            string cpid = "";
            try
            {
                var res = Cryptography.MsgDecrypt(msg.data, GetRequestKey(m_sEncodingAESKey, Convert.ToString(msg.timestamp)), ref cpid);
                Console.WriteLine(res);
                sd = Json.Decode<ServiceData>(res);
            }
            catch (FormatException)
            {
                return (int)TzjMsgCryptErrorCode.TzjMsgCrypt_DecodeBase64_Error;
            }
            catch (Exception e)
            {
                return (int)TzjMsgCryptErrorCode.TzjMsgCrypt_DecryptAES_Error;
            }
            if (cpid != m_sPlatID)
                return (int)TzjMsgCryptErrorCode.TzjMsgCrypt_ValidateAppid_Error;
            return 0;
        }

        //将企业号回复用户的消息加密打包
        // @param sReplyMsg: 企业号待回复用户的消息，xml格式的字符串
        // @param sTimeStamp: 时间戳，可以自己生成，也可以用URL参数的timestamp
        // @param sNonce: 随机串，可以自己生成，也可以用URL参数的nonce
        // @param sEncryptMsg: 加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串,
        //						当return返回0时有效
        // return：成功0，失败返回对应的错误码
        public int EncryptMsg(ServiceData sd, ref Message msg)
        {
            string raw = "";
            try
            {
                raw = Cryptography.MsgEncrypt(Json.Encode<ServiceData>(sd), GetRequestKey(m_sEncodingAESKey, Convert.ToString(msg.timestamp)), m_sPlatID);
            }
            catch (Exception e)
            {
                return (int)TzjMsgCryptErrorCode.TzjMsgCrypt_EncryptAES_Error;
            }
            msg.data = raw;
            string MsgSigature = "";
            int ret = 0;
            ret = GenarateSinature(m_sToken, Convert.ToString(msg.timestamp), msg.nonce, raw, ref MsgSigature);
            if (0 != ret)
                return ret;
            msg.signature = MsgSigature;
            return 0;
        }
        //GET RequestKey
        private static byte[] GetRequestKey(string aes_key, string timestamp)
        {

            //获取要加密的字段，并转化为Byte[]数组  
            byte[] data = System.Text.Encoding.UTF8.GetBytes((aes_key + timestamp).ToCharArray());
            //建立加密服务  
            System.Security.Cryptography.MD5 md5 = new System.Security.Cryptography.MD5CryptoServiceProvider();
            //加密Byte[]数组  
            return md5.ComputeHash(data);
            //return System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(aes_key + timestamp, "MD5").ToLower();
        }

        //Verify Signature
        private static int VerifySignature(string sToken, string sTimeStamp, string sNonce, string sMsgEncrypt, string sSigture)
        {
            string hash = "";
            int ret = 0;
            ret = GenarateSinature(sToken, sTimeStamp, sNonce, sMsgEncrypt, ref hash);
            if (ret != 0)
                return ret;
            //System.Console.WriteLine(hash);
            if (hash == sSigture)
                return 0;
            else
            {
                return (int)TzjMsgCryptErrorCode.TzjMsgCrypt_ValidateSignature_Error;
            }
        }

        public static int GenarateSinature(string sToken, string sTimeStamp, string sNonce, string sMsgEncrypt, ref string sMsgSignature)
        {
            ArrayList AL = new ArrayList();
            AL.Add(sToken);
            AL.Add(sTimeStamp);
            AL.Add(sNonce);
            AL.Add(sMsgEncrypt);
            AL.Sort(new DictionarySort());
            string raw = "";
            for (int i = 0; i < AL.Count; ++i)
            {
                raw += AL[i];
            }

            SHA1 sha;
            ASCIIEncoding enc;
            string hash = "";
            try
            {
                sha = new SHA1CryptoServiceProvider();
                enc = new ASCIIEncoding();
                byte[] dataToHash = enc.GetBytes(raw);
                byte[] dataHashed = sha.ComputeHash(dataToHash);
                hash = BitConverter.ToString(dataHashed).Replace("-", "");
                hash = hash.ToLower();
            }
            catch (Exception)
            {
                return (int)TzjMsgCryptErrorCode.TzjMsgCrypt_ComputeSignature_Error;
            }
            sMsgSignature = hash;
            return 0;
        }
        public class DictionarySort : System.Collections.IComparer
        {
            public int Compare(object oLeft, object oRight)
            {
                string sLeft = oLeft as string;
                string sRight = oRight as string;
                int iLeftLength = sLeft.Length;
                int iRightLength = sRight.Length;
                int index = 0;
                while (index < iLeftLength && index < iRightLength)
                {
                    if (sLeft[index] < sRight[index])
                        return -1;
                    else if (sLeft[index] > sRight[index])
                        return 1;
                    else
                        index++;
                }
                return iLeftLength - iRightLength;

            }
        }
    }
}
