<?php

/**
 * 模拟http请求
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Util
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:13:46
 */
class Touzhijia_Platform_Util_Http
{
	/**
	 * 模拟http请求
	 *
	 * @param string  $url 目标url
	 * @param string  $postdata 需要post的数据
	 * @param integer $timeout http请求超时时间(秒)
	 * @return array(integer, string) (http返回码, 获取到的页面内容)
	 */
	static public function doPost($url, $postdata, $timeout = 3)
	{
		$ch = curl_init();

		$userAgent  = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';
		$options = array(
				CURLOPT_URL             => $url,
				CURLOPT_SSL_VERIFYPEER  => false,
				CURLOPT_SSL_VERIFYHOST  => false,
				CURLOPT_AUTOREFERER     => 1,
				CURLOPT_POST            => 0,
				CURLOPT_HEADER          => 0,
				CURLOPT_COOKIEJAR       => null,
				CURLOPT_COOKIEFILE      => null,
				CURLOPT_FOLLOWLOCATION  => 1,
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_TIMEOUT         => $timeout,
				CURLOPT_USERAGENT       => $userAgent
				);

		if (empty($postdata)) {
			$options[CURLOPT_POST]       = 0;
		} else {
			$options[CURLOPT_POST]       = 1;
			$options[CURLOPT_POSTFIELDS] = $postdata;
		}   

		curl_setopt_array($ch, $options);
		$content = curl_exec($ch);
		$info = curl_getinfo($ch);
		if (curl_errno($ch) != 0) {
			echo curl_error($ch);
			return false;
		}   

		curl_close($ch);

		return array($info['http_code'], $content);
	}

}	
