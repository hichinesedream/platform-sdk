package com.touzhijia.platform.controller;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.touzhijia.platform.entity.Message;
import com.touzhijia.platform.entity.QueryReq;
import com.touzhijia.platform.entity.Redirect;
import com.touzhijia.platform.entity.ServiceData;
import com.touzhijia.platform.security.MessageCrypt;
import com.touzhijia.platform.service.PlatformException;
import com.touzhijia.platform.service.PlatformService;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

import javax.servlet.http.HttpServletResponse;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;

/**
 * Created by architect of touzhijia on 2016/3/1.
 */
@Controller
@RequestMapping("/")
public class PlatformController {

    @Autowired
    private PlatformService platformService;
    
    @Autowired
    private MessageCrypt messageCrypt;
    
    private GsonBuilder gsonBuilder = new GsonBuilder().setDateFormat("yyyy-MM-dd HH:mm:ss");
    
    @Value("${expireTime}")
    private int expireTime;

    @RequestMapping(method = RequestMethod.POST)
    @ResponseBody
    public Message command(@RequestBody Message msg, HttpServletResponse httpResp) throws Throwable {
    	if ((System.currentTimeMillis() / 1000) - msg.getTimestamp() > expireTime) {
    		throw new PlatformException(100, "时间戳过期");
    	}
    	ServiceData req = messageCrypt.decryptMsg(msg);
    	
    	Class<? extends PlatformService> claz = platformService.getClass();
    	Method[] methods = claz.getMethods();
    	Method method = null;
    	for (Method m : methods) {
    		if (m.getName().equals(req.getService())) {
    			method = m;
    			break;
    		}
    	}
    	if (method == null) {
        	throw new PlatformException(101, "错误的业务类型:" + req.getService());
    	}
    	Class<?>[] paramClasses = method.getParameterTypes();
    	if (paramClasses.length != 1) {
    		throw new PlatformException(101, req.getService() + "方法参数错误");
    	}
    	Gson gson = gsonBuilder.create();
    	Object param = gson.fromJson(req.getBody(), paramClasses[0]);
    	if (param instanceof QueryReq) {
    		QueryReq q = (QueryReq)param;
    		q.validate();
    	}
    	Object ret = null;
    	try {
    		ret = method.invoke(platformService, param);
    	} catch (InvocationTargetException e) {
    		throw e.getTargetException();
    	}
    	if (ret instanceof Redirect) {
    		Redirect r = (Redirect)ret;
    		httpResp.sendRedirect(r.getUri());
    	}
    	ServiceData resp = new ServiceData(req.getService(), gson.toJsonTree(ret));
    	return messageCrypt.encryptMsg(resp);
    }
}
