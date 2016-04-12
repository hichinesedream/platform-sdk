<?php

/**
 * 日志打印类
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Util
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-04-01 17:10:31
 */
class Touzhijia_Util_Log
{
	private static $_instance = null;

	private $_logPath     = null;
	private $_logFileName = null;
	private $_logPreName  = null;

	/**
	 * @var string 每一次web访问都会打印一系列日志, 用sessionId来将他们串起来
	 */
	private $_sessionId   = null;


	public static function getInstance()
	{
		if (self::$_instance == null) {
			self::$_instance = new self();
			self::$_instance->resetSessionId();
		}

		return self::$_instance;
	}


	public function __construct()
	{
		if (defined(LOG_PATH)) {
			$this->setLogPath(LOG_PATH);
		} else {
			$this->setLogPath('./logs');
		}

		$this->setLogFilePreName('default');
		$this->_logFileName = date('Ymd') . '.php';
	}


	public function resetSessionId()
	{
		// 获取当前秒数 YYMMDDHHIISS, 12 bytes
		$t = date('ymdHis');

		// 获取当前微秒数, 6 bytes
		list($usec, $sec) = explode(" ", microtime());
		$u = str_pad(intval($usec * 1000000), 6, '0', STR_PAD_LEFT);

		// 获取随机数, 2 bytes
		$rnd = rand(10, 99);

		// 拼成sessionId, 12+6+2=20 bytes
		$this->_sessionId = $t . $u . $rnd;
	}


	/**
	 * 设置日志路径
	 *
	 * @param string $path 路径
	 * @return Touzhijia_Util_Log
	 */
	public function setLogPath($path)
	{
		$this->_logPath = $path;
		return $this;
	}


	/**
	 * 设置日志文件前缀
	 *
	 * @param string $s 前缀
	 * @return Touzhijia_Util_Log
	 */
	public function setLogFilePreName($s)
	{
		$this->_logFilePreName = $s;
		return $this;
	}


	/**
	 * 打log
	 *
	 * @param string $logMsg 日志内容
	 * @param string $logLevel 日志等级
	 * @param string $logFilePreName 日志文件前缀
	 * @return Touzhijia_Util_Log
	 */
	public function log($logMsg, $logLevel = 'INFO', $logFilePreName = null)
	{
		if (empty($logFilePreName)) {
			$fileFullName = "{$this->_logPath}/{$this->_logFilePreName}_{$this->_logFileName}";
		} else {
			$fileFullName = "{$this->_logPath}/{$logFilePreName}_{$this->_logFileName}";
		}

		if (!file_exists($fileFullName)) {
			$logContent = "<?php die(); ?" . "> \n";
			file_put_contents($fileFullName, $logContent, FILE_APPEND);
		}

		$t = date('Y-m-d H:i:s');
		list($usec, $sec) = explode(" ", microtime());
		$u = str_pad(intval($usec * 1000000), 6, '0', STR_PAD_LEFT);

		$logMsg = $this->removeContinueBlankChars($logMsg);

		$logContent = "[{$t}:${u}][{$logLevel}][{$this->_sessionId}]: {$logMsg}\n";
		file_put_contents($fileFullName, $logContent, FILE_APPEND);
		return $this;
	}


	/**
	 * 删除连续的空白字符,方便日志打印
	 *
	 * @param string 待处理的字符串
	 * @return string
	 */
	private function removeContinueBlankChars($s)
	{
		if (empty($s)) {
			return $s;
		}

		$out = $s{0};
		$isPrevCharBlank = $this->isBlankChar($s{0});

		$len = strlen($s);
		for ($i = 1; $i < $len; $i++) {
			$currChar = $s{$i};
			$isCurrCharBlank = $this->isBlankChar($currChar);

			if ($isCurrCharBlank && $isPrevCharBlank) {
				continue;
			}

			if ($isCurrCharBlank) {
				$currChar = ' ';
			}

			$out .= $currChar;

			$isPrevCharBlank = $isCurrCharBlank;
		}

		return $out;
	}


	/**
	 * 判断给定的字符是否空白字符
	 *
	 * @param string
	 * @return bool
	 */
	private function isBlankChar($ch)
	{
		if ($ch == " " || $ch == "\n" || $ch == "\r" || $ch == "\t") {
			return true;
		}

		return false;
	}
}
