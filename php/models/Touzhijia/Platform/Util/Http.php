<?php


class Touzhijia_Platform_Util_Http
{
	static public function doPost($url, $data) 
	{
		if (is_array($data)) {
			$postData = http_build_query($data);
		} else {
			$postData = $data;
		}

		$opts = array (
				'http' => array (
					'method' => 'POST',
					'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
					          . "Content-Length: " . strlen($postData) . "\r\n",
					'content' => $postData
					)
			      );
		$context = stream_context_create($opts);
		$html = file_get_contents($url, false, $context);
		return $html;
	}
}	
