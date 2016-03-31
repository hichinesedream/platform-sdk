<?php
/**
 * 全局常量定义文件
 * 
 * @category   Touzhijia
 * @package    null
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 14:39:23
 */

// 对接投之家的合作平台唯一凭证密钥, 用于对通讯数据进行加密, 双方联调时分配
define('TZJ_PROTOCOL_AES_KEY', '123456788765432112345678');

// 对接投之家的合作平台唯一签名密钥, 用于对通讯数据进行签名, 双方联调时分配
define('TZJ_PROTOCOL_TOKEN',   'shitoujinrong');

// 对接投之家的合作平台唯一标识符, 双方联调时分配
define('TZJ_PROTOCOL_PLAT_ID', 'stjr');

// 对接投之家的协议约定, 请求包有效时间(秒)
define('TZJ_PROTOCOL_EXPIRE_SEC', 300);

// 对接投之家的协议约定, 投之家与合作平台老用户绑定, 需要经过合作平台的专属登陆页鉴定身份
define('TZJ_PROTOCOL_BIND_USER_URL', 'https://www.xinrong.com/login/tzj_login_new');

// 对接投之家的协议约定, 通过登录接口可以跳转到合作平台标的详情页
define('TZJ_PROTOCOL_BID_DETAIL_URL', 'https://www.xinrong.com/2.0/detail.shtml?sid=');

// 对接投之家的协议约定, 通过登录接口可以跳转到合作平台首页
define('TZJ_PROTOCOL_HOMEPAGE_URL', 'https://www.xinrong.com');

// 对接投之家的协议约定, 通过登录接口可以跳转到合作平台个人中心
define('TZJ_PROTOCOL_USER_CENTER_URL', 'https://www.xinrong.com/2.0/views/account/account_index.shtml');

