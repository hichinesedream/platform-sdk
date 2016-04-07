<?php

/**
 * 模拟http请求
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Util
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:13:46
 */
class Touzhijia_Util_Http
{
	/**
	 * 模拟http请求
	 *
	 * @param string  $url 目标url
	 * @param string  $postdata 需要post的数据
	 * @param integer $timeout http请求超时时间(秒)
	 * @return array(integer|bool, string) (http返回码|false, 获取到的页面内容|出错信息)
	 */
	static public function doPost($url, $postdata, $timeout = 3, $contentType = "application/json")
	{
		$ch = curl_init();

		$userAgent  = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.0.9) Gecko/20061206 Firefox/1.5.0.9';

		$arrHeaders = array(
				"Content-type: $contentType;charset='utf-8'",
				);

		$options = array(
				CURLOPT_URL             => $url,
				CURLOPT_SSL_VERIFYPEER  => false,
				CURLOPT_SSL_VERIFYHOST  => false,
				CURLOPT_AUTOREFERER     => true,
				CURLOPT_POST            => false,
				CURLOPT_HEADER          => false,
				CURLOPT_HTTPHEADER      => $arrHeaders,
				CURLOPT_COOKIEJAR       => null,
				CURLOPT_COOKIEFILE      => null,
				CURLOPT_FOLLOWLOCATION  => true,
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_TIMEOUT         => $timeout,
				CURLOPT_USERAGENT       => $userAgent
				);

		if (empty($postdata)) {
			$options[CURLOPT_POST]          = false;
		} else {
			$options[CURLOPT_POST]          = true;
			$options[CURLOPT_POSTFIELDS]    = $postdata;
		}   

		curl_setopt_array($ch, $options);
		$content = curl_exec($ch);
		$info = curl_getinfo($ch);
		if (curl_errno($ch) != 0) {
			return array(false, curl_error($ch));
		}   

		curl_close($ch);

		return array($info['http_code'], $content);
	}

}	
