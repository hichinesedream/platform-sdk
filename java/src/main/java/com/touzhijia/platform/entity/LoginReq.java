package com.touzhijia.platform.entity;

public class LoginReq {
	// 投之家用户名
	private String username;
	// 平台用户名
	private String usernamep;
	// 标的ID
	private String bid;
	// 登录类型：0:PC, 1:WAP
	private int type;
	
	public String getUsername() {
		return username;
	}
	public String getUsernamep() {
		return usernamep;
	}
	public String getBid() {
		return bid;
	}
	public int getType() {
		return type;
	}
}
