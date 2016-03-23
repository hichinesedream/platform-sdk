package com.touzhijia.platform.controller;

import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.bind.annotation.ResponseStatus;

import com.touzhijia.platform.entity.ErrorResponse;
import com.touzhijia.platform.service.PlatformException;

@ControllerAdvice
public class ErrorHandler {
	
	@ExceptionHandler(value = PlatformException.class)
    @ResponseStatus(HttpStatus.INTERNAL_SERVER_ERROR)
    @ResponseBody
    public ErrorResponse platformError(PlatformException exception) {
        return new ErrorResponse(exception.getCode(), 
        		exception.getMessage() == null ? "" : exception.getMessage());
    }
	
    @ExceptionHandler(value = Throwable.class)
    @ResponseStatus(HttpStatus.INTERNAL_SERVER_ERROR)
    @ResponseBody
    public ErrorResponse intervalServerError(Throwable exception) {
        return new ErrorResponse(HttpStatus.INTERNAL_SERVER_ERROR.value(), 
        		exception.getMessage() == null ? "" : exception.getMessage());
    }
}