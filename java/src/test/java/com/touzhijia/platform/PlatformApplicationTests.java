package com.touzhijia.platform;

import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.SpringApplicationConfiguration;
import org.springframework.http.MediaType;
import org.springframework.test.context.junit4.SpringJUnit4ClassRunner;
import org.springframework.test.context.web.WebAppConfiguration;
import org.springframework.test.web.servlet.MockMvc;
import org.springframework.test.web.servlet.ResultActions;
import org.springframework.test.web.servlet.request.MockMvcRequestBuilders;
import org.springframework.test.web.servlet.setup.MockMvcBuilders;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.touzhijia.platform.controller.PlatformController;
import com.touzhijia.platform.entity.CreateUserReq;
import com.touzhijia.platform.entity.LoginReq;
import com.touzhijia.platform.entity.Message;
import com.touzhijia.platform.entity.QueryReq;
import com.touzhijia.platform.entity.ServiceData;
import com.touzhijia.platform.entity.UserInfo;
import com.touzhijia.platform.security.MessageCrypt;

import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.status;
import static org.junit.Assert.assertEquals;

@WebAppConfiguration
@RunWith(SpringJUnit4ClassRunner.class)
@SpringApplicationConfiguration(classes = PlatformApplication.class)
public class PlatformApplicationTests {
	
	private MockMvc mvc;
	
	@Autowired
    private MessageCrypt messageCrypt;
	
	@Autowired
	private PlatformController controller;
	
	private Gson gson = new GsonBuilder().setDateFormat("yyyy-MM-dd HH:mm:ss").create();
	
	@Before
	public void setUp() {
		mvc = MockMvcBuilders.standaloneSetup(controller).build();
	}
	
	@Test
	public void invalidService() throws Exception {
		ResultActions actions = sendRequest("foobar", null);
		actions.andExpect(status().is5xxServerError());
	}
	
	@Test
	public void createUser() throws Exception {
		CreateUserReq req = new CreateUserReq();
		req.setUsername("leonardo");
		req.setTelephone("18922222222");
		req.setEmail("leonardo@gmail.com");
		
		ResultActions actions = sendRequest("createUser", req);
		actions.andExpect(status().isOk());
		ServiceData resp = getResponse(actions);
		
		assertEquals("createUser", resp.getService());
		UserInfo u = gson.fromJson(resp.getBody(), UserInfo.class);
		assertEquals("leonardo", u.getUsername());
		assertEquals("tzj_leonardo", u.getUsernamep());
	}
	
	@Test
	public void login() throws Exception {
		sendRequest("login", new LoginReq());
	}
	
	@Test
	public void queryUser() throws Exception {
		sendRequest("queryUser", new QueryReq());
	}
	
	private ResultActions sendRequest(String service, Object body) throws Exception {
		
		ServiceData sd = new ServiceData(service, gson.toJsonTree(body));
		Message msg = messageCrypt.encryptMsg(sd);
		String json = gson.toJson(msg);
		System.out.println(service);
		System.out.println(json);
		return mvc.perform(MockMvcRequestBuilders.post("/")
				.accept(MediaType.APPLICATION_JSON)
				.contentType(MediaType.APPLICATION_JSON)
				.content(json));
	}
	
	private ServiceData getResponse(ResultActions a) throws Exception {
		String content = a.andReturn().getResponse().getContentAsString();
		Message msg = gson.fromJson(content, Message.class);
		return messageCrypt.decryptMsg(msg);
	}

}
