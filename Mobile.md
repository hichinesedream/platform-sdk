# 移动版文档

# 1. 概述

移动版主要用的接口为createUser,bindUser,login 这三个接口。

其中createUser和login接口可以复用原来的接口URL，login接口依据type字段来区分用户SSO登陆到PC还是WAP端。bindUser 接口需要提供一个针对移动端适配的接口URL，数据传输协议不变。


