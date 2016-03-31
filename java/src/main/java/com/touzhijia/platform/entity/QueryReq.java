package com.touzhijia.platform.entity;

import java.util.Date;

import com.touzhijia.platform.service.PlatformException;

public class QueryReq {
	public class TimeRange {
		// 开始时间
		private Date startTime;
		// 结束时间
		private Date endTime;
		
		public Date getStartTime() {
			return startTime;
		}
		
		public void setStartTime(Date startTime) {
			this.startTime = startTime;
		}
		
		public Date getEndTime() {
			return endTime;
		}
		
		public void setEndTime(Date endTime) {
			this.endTime = endTime;
		}
	}
	
	public class Index {
		// 索引名字
		private String name;
		// 索引查询
		private String[] vals;
		
		public String getName() {
			return name;
		}
		
		public void setName(String name) {
			this.name = name;
		}
		
		public String[] getVals() {
			return vals;
		}
		
		public void setVals(String[] vals) {
			this.vals = vals;
		}
	}
	
	
	private TimeRange timeRange;
	
	private Index index;
	
	public TimeRange getDateRange() {
		return timeRange;
	}
	
	public void setDateRange(TimeRange timeRange) {
		this.timeRange = timeRange;
	}
	
	public Index getIndex() {
		return index;
	}
	
	public void setIndex(Index index) {
		this.index = index;
	}
	
	public void validate() throws PlatformException {
		if (index == null && timeRange == null) {
			throw new PlatformException(101, "参数有误");
		}
		if (timeRange != null && timeRange.startTime.after(timeRange.endTime)) {
			throw new PlatformException(101, "参数有误");
		}
	}
}
