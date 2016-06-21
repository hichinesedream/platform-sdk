<%@ WebHandler Language="C#" Class="Handler" %>

using System;
using System.Web;
using touzhijia;
using touzhijia.entity;
using touzhijia.util;

public class Handler : IHttpHandler {

    string token = "shitoujinrong";
    string platID = "stjr";
    string encodingAESKey = "123456788765432112345678";

    public void ProcessRequest (HttpContext context) {
        context.Response.ContentType = "application/json";
        System.IO.StreamReader sr = new System.IO.StreamReader(context.Request.InputStream);
        if (context.Request.HttpMethod != "POST")
        {
            context.Response.Write("must be post");
            return;
        }
        string req = sr.ReadToEnd();
        Platform platform = new Platform(token, encodingAESKey, platID, "");
        var msg = Json.Decode<Message>(req);
        Message nmsg = platform.Command(msg, null);
        var result = Json.Encode<Message>(nmsg);
        context.Response.Write(result);
    }

    public bool IsReusable {
        get {
            return false;
        }
    }

}