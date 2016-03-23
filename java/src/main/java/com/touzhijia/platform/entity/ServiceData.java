package com.touzhijia.platform.entity;

import com.google.gson.JsonElement;

public class ServiceData {
	// 业务标识
	private String service;
	// 业务请求或响应
	private JsonElement body;
	
	public ServiceData(String service, JsonElement body) {
		this.service = service;
		this.body = (JsonElement) body;
	}
	
	public String getService() {
		return service;
	}
	
	public JsonElement getBody() {
		return body;
	}
}
