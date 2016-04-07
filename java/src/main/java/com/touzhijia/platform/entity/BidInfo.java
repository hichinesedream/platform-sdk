package com.touzhijia.platform.entity;

import java.util.Date;

public class BidInfo {
	private String id;
	private String url;
	private String title;
	private String desc;
	private String borrower;
	private Float borrowAmount;
	private Float remainAmount;
	private Float minInvestAmount;
	private String period;
	private Float orginalRate;
	private Float rewardRate;
	private int status;
	private int repayment;
	private int type;
	private Date createAt;
	private Date publishAt;
	private Date closeAt;
	private Date fullAt;
	private Date repayDay;
	private String[] tags;
	
	public String getId() {
		return id;
	}
	public void setId(String id) {
		this.id = id;
	}
	public String getUrl() {
		return url;
	}
	public void setUrl(String url) {
		this.url = url;
	}
	public String getTitle() {
		return title;
	}
	public void setTitle(String title) {
		this.title = title;
	}
	public String getDesc() {
		return desc;
	}
	public void setDesc(String desc) {
		this.desc = desc;
	}
	public String getBorrower() {
		return borrower;
	}
	public void setBorrower(String borrower) {
		this.borrower = borrower;
	}
	public Float getBorrowAmount() {
		return borrowAmount;
	}
	public void setBorrowAmount(Float borrowAmount) {
		this.borrowAmount = borrowAmount;
	}
	public Float getRemainAmount() {
		return remainAmount;
	}
	public void setRemainAmount(Float remainAmount) {
		this.remainAmount = remainAmount;
	}
	public Float getMinInvestAmount() {
		return minInvestAmount;
	}
	public void setMinInvestAmount(Float minInvestAmount) {
		this.minInvestAmount = minInvestAmount;
	}
	public String getPeriod() {
		return period;
	}
	public void setPeriod(String period) {
		this.period = period;
	}
	public Float getOrginalRate() {
		return orginalRate;
	}
	public void setOrginalRate(Float orginalRate) {
		this.orginalRate = orginalRate;
	}
	public Float getAwardReate() {
		return awardReate;
	}
	public void setAwardReate(Float awardReate) {
		this.awardReate = awardReate;
	}
	public int getStatus() {
		return status;
	}
	public void setStatus(int status) {
		this.status = status;
	}
	public int getRepayment() {
		return repayment;
	}
	public void setRepayment(int repayment) {
		this.repayment = repayment;
	}
	public int getType() {
		return type;
	}
	public void setType(int type) {
		this.type = type;
	}
	public Date getCreateAt() {
		return createAt;
	}
	public void setCreateAt(Date createAt) {
		this.createAt = createAt;
	}
	public Date getPublishAt() {
		return publishAt;
	}
	public void setPublishAt(Date publishAt) {
		this.publishAt = publishAt;
	}
	public Date getCloseAt() {
		return closeAt;
	}
	public void setCloseAt(Date closeAt) {
		this.closeAt = closeAt;
	}
	public Date getFullAt() {
		return fullAt;
	}
	public void setFullAt(Date fullAt) {
		this.fullAt = fullAt;
	}
	public Date getRepayDay() {
		return repayDay;
	}
	public void setRepayDay(Date repayDay) {
		this.repayDay = repayDay;
	}
	public String[] getTags() {
		return tags;
	}
	public void setTags(String[] tags) {
		this.tags = tags;
	}
}
