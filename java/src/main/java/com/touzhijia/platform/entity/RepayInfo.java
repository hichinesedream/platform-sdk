package com.touzhijia.platform.entity;

import java.util.Date;

public class RepayInfo {
	// 订单ID
	private String id;
	// 投资订单ID
	private String investId;
	// 投资者投之家用户名
	private String username;
	// 回款金额
	private Float amount;
	// 回款利率
	private Float rate;
	// 回款收益
	private Float income;
	// 回款类型：0:普通回款，1:转让回款
	private int type;
	// 回款时间
	private Date repayAt;
	// 标签
	private String[] tags;
	
	public String getId() {
		return id;
	}
	public void setId(String id) {
		this.id = id;
	}
	public String getInvestId() {
		return investId;
	}
	public void setInvestId(String investId) {
		this.investId = investId;
	}
	public String getUsername() {
		return username;
	}
	public void setUsername(String username) {
		this.username = username;
	}
	public Float getAmount() {
		return amount;
	}
	public void setAmount(Float amount) {
		this.amount = amount;
	}
	public Float getRate() {
		return rate;
	}
	public void setRate(Float rate) {
		this.rate = rate;
	}
	public Float getIncome() {
		return income;
	}
	public void setIncome(Float income) {
		this.income = income;
	}
	public int getType() {
		return type;
	}
	public void setType(int type) {
		this.type = type;
	}
	public Date getRepayAt() {
		return repayAt;
	}
	public void setRepayAt(Date repayAt) {
		this.repayAt = repayAt;
	}
	public String[] getTags() {
		return tags;
	}
	public void setTags(String[] tags) {
		this.tags = tags;
	}
}
