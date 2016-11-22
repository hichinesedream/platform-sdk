# 投之家接口文档

# 1. 概述

## 1.1 关于投之家

投之家（touzhijia.com）2014年9月16日正式上线运营，是国内首家专业的P2P网贷垂直搜索引擎。

投之家秉承“精选、分散”的安全投资理念，旨在通过严格的风控管理、创新的互联网技术，为用户提供优质靠谱的搜索、比较、一站式投资服务，让用户的投资理财变得安全、简单、高效。

投之家拥有行业内规模最大最专业的网贷平台风控考察团队，累计走访了500多家P2P平台，并对180多家平台从风控、法务、财务、运营、IT等多方面进行严格的实地认证。只有通过风控团队实地认证的平台，才会被投之家收录，确保平台的真实可靠性。
投之家网贷研究院经过近三年的数据研究和积累，首家推出了网贷安全评级，通过60多个指标，从六个维度全方位展示平台的真实运营情况，让投资风险、收益一目了然，辅助投资人更好地做出投资决策。

## 1.2 设计背景

投之家与优质的P2P平台合作，通过技术手段对接，为给用户提供良好的一站式登录、一站式投资体验，既为用户提供了安全、方便、快捷的网贷投资服务，也为合作平台带来了更多的用户。

## 1.3 文档范围

此文档面向接口开发者介绍投之家的接口协议、如何实现、相应示例，以及相关注意事项。

## 1.4 阅读对象

投之家平台、P2P网贷平台的商务经理、产品经理、开发人员、测试人员、运维人员。

# 2. 总体说明

## 2.1 接口调用

投之家通过`HTTP POST`的方式调用平台实现的接口

平台提供单一入口的URL(除了bindUser和login接口),用来接收调用请求

### 调用方式

```shell
curl -x POST -d '<data>' '(http|https)://<url>'
```

## 2.2 数据格式

无论是请求还是响应，数据格式都是JSON，且数据都须满足一定的Schema（模式）,Schema定义如下

```json
{
  "data": "string, required",
  "timestamp": "int64, required",
  "nonce": "string, required",
  "signature": "string, required"
}
```

### data原始数据模式定义

```json
{
	"service": "string, required, 用于区分不同的接口调用",
	"body": "object, optional, 请求数据，JSON格式，具体参考接口定义章节"
}
```

### Schema各字段解释

字段名 | 类型 | 必填 | 描述
--- | --- | --- | ---
data | string | Yes | 请求或响应数据，该字段为加密后的数据（加密算法请参考数据安全性章节），加密前的原始数据是`JSON`格式（参考data原始数据模式定义）
timestamp | int64 | Yes | 时间戳，数据发送的当前时间，在数据加密和校验时有重要作用
nonce | string | Yes | 随机字符串，用于data的签名，如果data为空，该字段也为空
signature | string | Yes | 对data的签名，如果data为空，该字段也为空，具体签名算法见`2.3.4`章节

### 错误响应

如果程序逻辑出错，需要返回错误的响应，错误响应的`HTTP Status Code`定义为`500`，错误的响应内容要有统一的格式，定义如下

```json
{
  "code": "int, 错误代码，具体见接口定义章节",
  "message": "错误描述"
}
```

### 其他说明

- 字符编码使用`UTF-8`
- 协议中所有时间参数（如`registerAt`, `investAt`等等）格式均为`2006-01-02 15:04:05`

## 2.3 数据安全

为保证数据安全性，对每个接口的调用（无论是请求还是响应）都需要加密，下面具体的描述了加密的细则

### 2.3.1 时间戳

这里指2.2章节中提到的协议中的时间戳。

程序逻辑在处理接收到的请求或响应时，需要根据该时间戳校验协议的时效性，如果

```
当前时间戳 - 协议时间戳 > 5分钟
```

则需要拒绝该请求或者响应。


### 2.3.2 密钥

投之家会为每个平台分配一个密钥（Secret），为了保证密钥的安全性，在每次请求时都会生成一个请求密钥，使用请求密钥对数据进行加密。

```php
// 请求密钥的生成方法为
ReqKey = MD5(Secret + Timestamp)
```

变量名 | 描述
--- | ---
ReqKey | 请求密钥，用于加密请求或响应数据
Secret | 分配密钥，投之家为每个平台分配
Timestamp | 时间戳，单位秒，数据发送的当前时间

### 2.3.3 加解密

默认使用`AES`方式对数据进行加密，数据加密前会对数据做一些简单的处理。

使用`Data`表示真实的明文数据

```php
// 加密算法
RawData = RandomStr + DataLength + Data + PlatId;
EncryptData = AESEncrypt(RawData, ReqKey);
```

变量名 | 描述
--- | ---
RandomStr | 16个字节的随机字符串
DatadLength | 数据长度，固定4个字节
Data | 明文数据
PlatId | 投之家分配给平台的ID
RawData | 加密前的数据
EncryptData | 加密后的数据
ReqKey | 请求密钥

### 2.3.4 数据签名

为了防止数据被篡改，对加密后的数据进行签名，接收到数据后会需要校验签名。

```php
// 数据签名算法
Signature = SHA1(sort(EncryptData, Token, Timestamp, Nonce));
```

变量名 | 描述
--- | ---
EncryptData | 加密后的数据
Token | 投之家分配给平台的Token
Timestamp | 时间戳，对应2.2中的timestamp
Nonce | 随机字符串，对应2.2中的timestamp
Signature | 签名

# 3. 接口描述

## 3.1 创建新账户

投之家通过该接口，为投之家用户在合作平台创建一个新的帐号。

### Service

`service=createUser`

### Request

```json
{
  "username": "string, 投之家用户名",
  "telephone": "string, 手机",
  "email": "string, 电子邮箱(可选)",
  "idCard": {
    "number": "string, 身份证号码",
    "name": "string, 实名"
  }, 
  "bankCard": {
	  "number": "string, 卡号",
	  "bank": "string, 银行名称",
	  "branch": "string, 支行名",
	  "province": "string, 省份",
	  "city": "string, 城市",
  },
  "tags": "array, 标签 (wap,pc)"
}
```

### Response

```json
{
  "username": "string, required, 投之家用户名",
  "usernamep": "string, required, 平台用户名",
  "registerAt": "datetime, required, 平台注册时间",
  "bindAt": "datetime, required, 绑定投之家时间",
  "bindType": "enum, 0:表示投之家带来的新用户",
  "tags": "array, 标签"
}
```

如果投之家用户重复创建，同样视为成功，返回对应的绑定信息。

### Errors

code | message
--- | ---
1001 | 手机号已占用
1002 | 邮箱已占用
1003 | 身份证已占用


## 3.2 关联老账户

投之家通过该接口，将用户在投之家的帐号跟在合作平台的帐号关联起来。

### Service

`service=bindUser`

### Request

```json
{
  "username": "string, 投之家用户名",
  "telephone": "string, 手机",
  "email": "string, 电子邮箱(可选)",
  "idCard": {
    "number": "string, 身份证号码",
    "name": "string, 实名"
  },
  "bankCard": {
	  "number": "string, 卡号",
	  "bank": "string, 银行名称",
	  "branch": "string, 支行名",
	  "province": "string, 省份",
	  "city": "string, 城市",
  },
  "tags": "array, 标签 (wap,pc)"
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
  "tags": "array, 标签"
}
```

请求回调的Method 为 `POST` 参数为
`data=xxx&nonce=xxx&signature=xxx&timestamp=12345643&appId=xxxx`


### Response

投之家会输出提示用户绑定成功的页面


## 3.3 单点登录

投之家与合作平台帐户关联的用户，通过该接口登录到合作平台。

### Service

`service=login`

### Request

```json
{
  "username": "string, 投之家用户名",
  "usernamep": "string, 合作平台用户名",
  "bid": "string, 标的ID，跳转到标的购买页，home为首页，account为个人中心",
  "type": "登录类型，0:PC，1:WAP"
}
```


请求回调的Method 为 `POST` 参数为
`data=xxx&nonce=xxx&signature=xxx&timestamp=12345643`

### Response

该接口为非应答接口，而是平台设置该用户的登录态，并进行浏览器跳转


## 3.4 用户信息查询

投之家通过该接口获取到合作平台的绑定用户信息

### Service

`service=queryUser`

### Request

```json
{
	"timeRange": {
		"startTime": "开始时间",
		"endTime": "结束时间"
	},
	"index": {
		"name": "这里只会根据投之家用户名查询，固定为username",
		"vals": "username数组，查询匹配的用户信息"
	}
}
```

可以根据时间的范围查询（timeRange）这个时间范围内完成绑定的所有用户信息，也可以根据索引查询（index）指定用户名的用户信息

### Response
```json
[
	{
	  "username": "string, required, 投之家用户名",
	  "usernamep": "string, required, 平台用户名",
	  "registerAt": "datetime, required, 平台注册时间",
	  "bindAt": "datetime, required, 绑定投之家时间",
	  "bindType": "enum, 0:表示投之家带来的新用户，1:表示平台已有用户",
	  "assets": {
		  "awaitAmount": "float，待收金额",
		  "balanceAmount": "float, optional, 账户余额",
		  "totalAmount": "float, optional, 资产总额"
	  },
	  "coupons": {
		  {"name": "string, 券的名字", "num": "int, 券的数量"}
		  {"name": "string, 券的名字", "num": "int, 券的数量"}
		  ......
	  },
	  "tags": "array, 标签"
	}
]
```


## 3.5 标的信息查询

投之家通过该接口获取到合作平台的标的信息，用于

- 投之家向用户展示合作平台标的
- 更新标的状态

### Service

`service=queryBids`

### Request

```json
{
	"timeRange": {
		"startTime": "开始时间",
		"endTime": "结束时间"
	},
	"index": {
		"name": "这里只会根据标的ID查询，固定为id",
		"vals": "array，标ID数组"
	}
}
```

可以根据时间的范围查询（timeRange）这个时间范围内**创建的所有标**的信息，也可以根据索引查询（index）指定标的ID的标的信息


### Response

```json
[
	{
	  "id": "string, 标的ID",
	  "url": "string, 标的URL",
	  "title": "string, 标题",
	  "desc": "string, 描述",
	  "borrower": "string, 借款人",
	  "borrowAmount": "float, 借款金额",
	  "remainAmount": "float, 剩余金额",
	  "minInvestAmount": "float, 起投金额",
	  "period": "string, 借款期限, 1d, 1m，如果为活期该字段为0",
	  "originalRate": "float, 原始年化利率，13.5表示13.5%",
	  "rewardRate": "float, 奖励利率，13.5表示13.5%",
	  "status": "enum, 标的状态，见标的状态表格",
	  "repayment": "string, 还款方式",
	  "type": "enum, 见标的类型表格",
	  "prop": "string，标的性质,比如:车贷，房贷，信用贷、融资租赁、保理等等",
	  "createAt": "datetime, 标的创建时间",
	  "publishAt": "datetime, 标的起投时间，如果有倒计时，这个时间会晚于标的创建时间",
	  "closeAt": "datetime, 标的截止购买时间",
	  "fullAt": "datetime, 标的满标时间",
	  "repayDate": "date, 预计还款日期(最后一期)",
	  "tags": "标签，数组，用以扩充标的属性。如：标的活动信息"
	}
]
```

#### 标的类型

type | 说明
--- | ---
0 | 普通标（固定期限，如10天，15天，1个月）
1 | 转让标
2 | 净值标
10 | 活期 
101 | 新手标（新手标也是一个普通的固定期限标，仅限新手投资）
102 | 体验金标
1000 | 其他（如：秒还标）

#### 标的状态

status | 说明
--- | ---
0 | 还款中
1 | 已还清
2 | 逾期
3 | 投标中
4 | 流标
5 | 撤标
6 | 满标
7 | 放款
8 | 等待放款
9 | 等待开始
99 | 其他


## 3.6 投资记录查询

投之家通过该接口获取到投之家用户在合作平台的投资记录，展示在投之家的个人中心，用户可以通过投资记录，了解在各个平台的投资情况，并通过链接再次进入到合作平台，从而为合作平台导流。

### Service

`service=queryInvests`

### Request

```json
{
	"timeRange": {
		"startTime": "开始时间",
		"endTime": "结束时间"
	},
	"index": {
		"name": "id OR bid OR username",
		"vals": "array，见下面的说明"
	}
}
```

可以根据时间的范围查询（timeRange）这个时间范围内发生的所有投资记录，也可以根据索引查询，进行如下查询

- id查询（name="id"），查询指定投资ID的投资记录，一个id对应一条投资记录
- bid查询（name="bid"），查询指定标的ID的投资记录列表，一个bid对于多条投资记录
- username（name="username"），查询制定用户的一段时间范围内的投资记录，**必须有timeRange参数**

### Response

```json
[
	{
	  "id": "string, 投资记录ID，全局唯一",
	  "bid": "string, 标的ID",
	  "burl": "string, 标的url",
	  "username": "string, 合作平台用户名",
	  "amount": "float, 投资金额",
	  "actualAmount": "float, 实际投资金额",
	  "income": "float, 预期投资收益",
	  "investAt": "datetime, 投资时间",
	  "tags": "array, 标签"
	}
]
```

tips: 从移动端投资用`wap`标识，Andoird客户端用`android` iOS客户端用`ios`标识，其他用`pc`标识，自动购买用`auto_buy`标识

## 3.7 回款记录查询

### Service

`service=queryRepays`

### Request

```json
{
	"timeRange": {
		"startTime": "开始时间",
		"endTime": "结束时间"
	},
	"index": {
		"name": "id OR bid OR username",
		"vals": "array，见下面的说明"
	}
}
```

可以根据时间的范围查询（timeRange）这个时间范围内发生的所有回款记录，也可以根据索引进行如下查询

- id查询（field="id"），查询指定回款ID的回款记录，一个id对应一条回款记录
- bid查询（field="bid"），查询指定标的ID对应的回款记录，一个标的ID对应一条或者多条（比如：分多期回款的）回款记录
- username（field="username"），查询制定用户的一段时间范围内的回款记录，**必须有timeRange参数**

### Response

```json
[
	{
		"id": "string, 回款ID，全局唯一",
		"investId": "string，投资ID",
		"bid": "string，标的ID",
		"username": "string, 合作平台用户名",
		"amount": "float, 回款金额(本金)",
		"income": "float, 回款收益(不包含管理费)",
		"repayAt": "datetime, 回款时间",
		"type": "enum，回款类型，见回款类型表格",
		"tags": "array, 标签"
	}
]
```

#### 回款类型

type | 说明
--- | ---
0 | 普通回款，因标的正常到期收到回款
1 | 转让回款，因用户主动债权转让收到回款



## 3.8 投之家用户信息查询

平台通过该接口获取到跟投之家绑定的用户信息

### Service

`service=queryTzjUser`

### Request

```json
{
	"timeRange": {
		"startTime": "开始时间",
		"endTime": "结束时间"
	},
	"index": {
		"name": "这里只会根据平台用户名查询，固定为usernamep",
		"vals": "username数组，查询匹配的用户信息"
	}
}
```

可以根据时间的范围查询（timeRange）这个时间范围内完成绑定的所有用户信息，也可以根据索引查询（index）指定平台用户名在投之家的用户信息
查询投之家用户信息接口地址：http://open.api.touzhijia.cn/query
请求的Method 为 `POST` 参数为
`data=xxx&nonce=xxx&signature=xxx&timestamp=12345643&appId=xxxx`

### Response

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
	  "branch": "string, 支行名",
	  "province": "string, 省份",
	  "city": "string, 城市",
  }
}
```


# 4. 异常

下表为每个接口公用的一些异常

type| name | 说明 
--- | ---- | ---  
101 | MISSING_SERVICE_NAME     | 缺少 Service Name 
102 | UNKNOWN_SERVICE_ERROR    | Service Name不存在
103 | VALIDATE_SIGNATURE_ERROR | 签名验证失败
104 | VALIDATE_TIMESTAMP_ERROR | 时间戳过期
105 | VALIDATE_APPID_ERROR 	   | AppID校验失败
106 | PARSE_JSON_ERROR 		   | JSON反序列化出错
107 | GEN_RETURN_MSG_ERROR	   | 生成返回包失败
108 | COMPUTE_SIGNATURE_ERROR  | 生成签名失败
109 | ENCRYPT_AES_ERROR		   | 加密失败
110 | DECRYPT_AES_ERROR		   | 解密失败
201 | INVALID_PARAMETER		   | 请求参数出错
202 | USER_NOT_EXISTS		   | 用户不存在
203 | START_GREAT_THAN_END	   | startTime 不能大于endTime
204 | TIME_RANGE_EXCEED		   | 时间查询跨度不能超过72小时
205 | QUERY_ITEM_COUNT_EXCEED  | 查询项数量过多
500 | APPLICATION_ERROR        | 系统异常
1001 | TELEPHONE_HAVE_USED     | 手机号已占用
1002 | EMAIL_HAVE_USED 		   | 邮箱号已占用
1003 | IDCARD_HAVE_USED 	   | 身份证号已占用
