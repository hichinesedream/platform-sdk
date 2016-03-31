<?php

/**
 * MVC framework
 *
 * 一个很简单的框架，把用户请求映射到控制器类的指定方法，以便减小规模，进行处理
 *
 * URL mode:
 * <samp>
 *	http://<your_url>/<controller>/<action>
 *	http://<your_url>/index.php/<controller>/<action>
 * </samp>
 *
 * URL example:
 * <samp>
 *	http://localhost/myctrl/myact
 *	http://localhost/index.php/myctrl/myact
 * </samp>
 *
 * Note:
 *	Need nginx or apache rewrite support
 *
 * Apache htaccess file in the root Directory: .htaccess
 * <samp>
 *	RewriteEngine on
 *	RewriteCond $1 !^(index.php|css|js|images|robots.txt)  
 *	RewriteRule ^(.*)$ /index.php?/$1 [L]
 * </samp>
 *
 * @category   Touzhijia
 * @package    null
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 14:37:43
 */


// 环境设置
error_reporting(E_ALL | E_NOTICE);
date_default_timezone_set('Asia/Chongqing');
 
// URL配置
define('WEB_ROOT',	(dirname($_SERVER['SCRIPT_NAME']) == '/') ? '' : dirname($_SERVER['SCRIPT_NAME']));
define('DOC_ROOT',	$_SERVER['DOCUMENT_ROOT'] . WEB_ROOT);
define('CUR_URL',	($_SERVER['SERVER_PORT'] == 80)
			? "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}"
			: "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$_SERVER['REQUEST_URI']}");

// 相对路径配置
define('CONF_DIR',	'config');
define('CTRLLER_DIR',	'controllers');
define('MODEL_DIR',	'models');
define('TMPL_DIR',	'templates');
define('LOG_DIR',	'logs');

// 绝对路径配置
define('CONF_PATH',     DOC_ROOT . '/' . CONF_DIR);
define('CTRLLER_PATH',  DOC_ROOT . '/' . CTRLLER_DIR);
define('MODEL_PATH',    DOC_ROOT . '/' . MODEL_DIR);
define('TMPL_PATH',     DOC_ROOT . '/' . TMPL_DIR);
define('LOG_PATH',      DOC_ROOT . '/' . LOG_DIR);


/**
 * 自动类载入
 * 当php脚本调用一个类，而该类并无require进来，php引擎就会自动调用该函数
 * 本函数对按照框架约定的类名，自动找到类所在的文件，并包含进来
 * 
 * 搜索路径： 
 *   (1) ./models 目录  
 *   (2) php.ini定义的include_path目录
 *
 * @param string $className 类名
 */
function __autoload($className) {
	$fullFileName = str_replace("_", "/", $className) . ".php";

	// 优先搜寻框架文件夹中的models目录
	$fullFileNameWithPath = "./" . MODEL_DIR . "/{$fullFileName}";
	if (file_exists($fullFileNameWithPath)) {
		require_once($fullFileNameWithPath);
		return;
	}

	// 否则php按照配置文件中的搜寻路径自动寻找包含
	require_once($fullFileName);
}


/**
 * MVC framework
 *
 * 一个很简单的框架，把用户请求映射到控制器类的指定方法，以便减小规模，进行处理
 *
 * @package    null
 * @copyright  Copyright (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.Touzhijia.com)
 * @author     jamesqin <qinwq@touzhijia.com>
 * @version    1.0.0
 */
class Framework
{
	static public function getPathInfo()
	{
		if (isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])) {
			return $_SERVER['PATH_INFO'];
		}

		$len = strlen($_SERVER['SCRIPT_NAME']);
		if (substr($_SERVER['REQUEST_URI'], 0, $len) == $_SERVER['SCRIPT_NAME']) {
			$pathinfo = substr($_SERVER['REQUEST_URI'], $len);
			return $pathinfo;
		}

		return $_SERVER['REQUEST_URI'];
	}


	/**
	 * 从url中取controller参数作为控制器名，取action参数作为动作名，自动分发请求到指定的类中的指定方法
	 */
	static public function parseRoute()
	{
		// [PATH_INFO] process
		$strPathInfo = trim(self::getPathInfo(), "/");
		$arrPathInfo = explode('/', $strPathInfo);
		$iPathInfoLen = count($arrPathInfo);

		// controller
		if ($iPathInfoLen >= 1) {
			$_REQUEST['controller'] = $_GET['controller'] = $arrPathInfo[0];
		}

		// action
		if ($iPathInfoLen >= 2) {
			$_REQUEST['action'] = $_GET['action'] = $arrPathInfo[1];
		}

		// other parameter
		for ($i = 2; $i < $iPathInfoLen; $i+=2) {
			$key = $arrPathInfo[$i];
			$val = ($i+1 < $iPathInfoLen) ? $arrPathInfo[$i+1] : null;
			$_REQUEST[$key] = $_GET[$key] = $val;
		}

		// controller
		if (array_key_exists('controller', $_REQUEST)) {
			$controller = empty($_REQUEST['controller']) ? 'index' : strtolower($_REQUEST['controller']);
		} else {
			$controller = 'index';
		}

		define("CONTROLLER", $controller);

		// action
		if (array_key_exists('action', $_REQUEST)) {
			$action = strtolower($_REQUEST['action']);
		} else {
			$action = "index";
		}

		define("ACTION", $action);
	}


	static public function run($isNeedParseRoute = true)
	{
		if ($isNeedParseRoute) {
			self::parseRoute();
		}

		// call it
		$ctlName = ucfirst(CONTROLLER) . 'Controller';
		if (!file_exists('./' . CTRLLER_DIR . '/' . $ctlName . '.php')) {
			echo "Controller {$ctlName} not exists";exit;
			throw new Exception("Controller {$ctlName} not exists.");
		}

		$actName = ACTION . "Action";

		require_once('./' . CTRLLER_DIR . '/' . $ctlName . '.php');
		$app = new $ctlName();
		if (!method_exists($app, $actName)) {
			// don't throw exception, because php has magic method __call($method, $args)
			// echo "Action:{$actName} not exists"; exit;
		}

		$app->$actName();
	}
}


// cli支持
if (isset($_SERVER['SHELL'])) {
	if ($argc > 1) {
		$_SERVER['SERVER_NAME'] = 'localhost';
		$_SERVER['SERVER_PORT'] = 80;
		$_SERVER['PATH_INFO']   = $argv[1];
		$_SERVER['REQUEST_URI'] = $argv[1];
	}

	if ($argc > 2) {
		$arrQuery = parse_str($argv[2]);
		if (is_array($arrQuery)) {
			foreach ($arrQuery as $k => $v) {
				$_REQUEST[$k] = $v;
			}
		}
	}
}

// do it
require_once(CONF_PATH . '/defines.php');
Framework::run();


