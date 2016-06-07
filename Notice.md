# Notice

###平台对接注意事项

- 接口URL 最多只能两个URL，可以整合为一个URL；bindUser和sso登陆为一个接口URL，其他5个接口为
- 错误响应的HTTP 状态码为500,老接口的状态码为406
- 协议中所有时间参数的请求和返回都为”2006-01-02 15:04:05”
- createUser 和bindUser 两个接口返回的平台用户名要保证唯一性，即用户更改用户名或者手机号不影响到其他功能。同时queryUser, queryInvest和queryRepay 中的username 与创建时的用户名保持一致。
- queryBids接口中的period 一定要带上单位，d为天，m为月。
- queryInvest和queryRepay中的username 为合作平台方的用户名。
- createUser 注册新用户，必须给用户发送默认的登陆密码。
- bindUser 支持两种方式回调，API形式的回调，和页面隐藏表单的回调
- bindUser 回调的参数中多一个appid，用于识别合作平台方。
- bindUser 用户多次请求绑定的时候，请忽略掉错误，直接回调投之家的接口。
- 回传接口中的字段严格按照协议返回，如果遇到空值，可以回传默认值。datetime 为空时，设置为NULL。
- queryUser接口 按照username查询 不会带timeRange参数
- queryBids接口 按照id查询 是不会带timeRange的参数
- queryInvests接口 按照username查询需要带timeRange
按照id/bid 查询，不会带timeRange参数
- queryRepay接口 按照username查询需要带timeRange
按照id/bid 查询，不会带timeRange参数
