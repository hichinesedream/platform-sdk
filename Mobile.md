# 移动版文档

# 1. 概述

移动版主要用的接口为createUser,bindUser,login 这三个接口。

其中createUser和login接口可以复用原来的接口URL，login接口依据type字段来区分用户SSO登陆到PC还是WAP端。bindUser 接口需要提供一个针对移动端适配的接口URL，数据传输协议不变。




# 2. 关联老账户

投之家通过该接口，将用户在投之家的帐号跟在合作平台的帐号关联起来。

### Service

`service=bindUser`

### Request

```json
{
  "username": "string, 投之家用户名",
  "telephone": "string, 手机",
  "email": "string, 电子邮箱",
  "idCard": {
    "number": "string, 身份证号码",
    "name": "string, 实名"
  },
  "bankCard": {
	  "number": "string, 卡号",
	  "bank": "string, 银行名称",
	  "branch": "string, 支行名"
  }
}
```

### Response

合作平台接收到该请求后，需要将用户带到投之家与合作平台的专属绑定页面，验证用户身份。验证成功后，完成绑定。
用户授权绑定成功后平台需同步回调投之家的接口URL.

	 线上地址：http://open.api.touzhijia.cn/callback
	 测试地址：http://test.touzhijia.com:3333/callback

此时请求的URL 所需参数为：
### Service
`service=bindUser`
### Request
```json
{
  "username": "string, required, 投之家用户名",
  "usernamep": "string, required, 平台用户名",
  "registerAt": "datetime, required, 平台注册时间",
  "bindAt": "datetime, required, 绑定投之家时间",
  "bindType": "enum, 1:表示平台已有用户",
  "type":  "登录类型，0:PC，1:WAP"
  "tags": "array, 标签"
}
```

请求回调的Method 为 `POST` 参数为
`data=xxx&nonce=xxx&signature=xxx&timestamp=12345643&appId=xxxx`


### Response

投之家会输出提示用户绑定成功的页面
