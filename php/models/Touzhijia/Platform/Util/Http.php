<?php


class Touzhijia_Platform_Util_Http
{
	static public function doPost($url, $postdata)
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
				CURLOPT_TIMEOUT         => 30,
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
