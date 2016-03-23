package com.touzhijia.platform.service;

public class PlatformException extends Exception {
	private static final long serialVersionUID = -2125031025287319917L;

	private int code;
	
	public PlatformException(int code, String message) {
		super(message);
		this.setCode(code);
	}
	
	public int getCode() {
		return code;
	}

	public void setCode(int code) {
		this.code = code;
	}

}
