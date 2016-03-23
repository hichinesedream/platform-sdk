package com.touzhijia.platform.entity;

import java.util.Date;

public class InvestInfo {
	private String id;
	private String bid;
	private String burl;
	private String username;
	private Float amount;
	private Float actualAmount;
	private Float income;
	private Date investAt;
	private Date repayAt;
	private String[] tags;
	public String getId() {
		return id;
	}
	public void setId(String id) {
		this.id = id;
	}
	public String getBid() {
		return bid;
	}
	public void setBid(String bid) {
		this.bid = bid;
	}
	public String getBurl() {
		return burl;
	}
	public void setBurl(String burl) {
		this.burl = burl;
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
	public Float getActualAmount() {
		return actualAmount;
	}
	public void setActualAmount(Float actualAmount) {
		this.actualAmount = actualAmount;
	}
	public Float getIncome() {
		return income;
	}
	public void setIncome(Float income) {
		this.income = income;
	}
	public Date getInvestAt() {
		return investAt;
	}
	public void setInvestAt(Date investAt) {
		this.investAt = investAt;
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
