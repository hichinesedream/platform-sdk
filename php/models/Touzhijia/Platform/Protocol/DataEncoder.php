<?php

/**
 * 提供接收和推送给投之家消息的消息编码接口
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Protocol
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:48:56
 */
class Touzhijia_Platform_Protocol_DataEncoder
{
	/**
	 * 对明文进行格式化 RawData = RandomStr + DataLength + Data + PlatId
	 *
	 * @param string $text  需要编码的明文
	 * @param string $appid 合作平台id,该appid由联调时确定
	 * @return string 编码后的密文
	 */
	static public function encode($text, $appid)
	{
		//获得16位随机字符串，填充到明文之前
		$random = Touzhijia_Platform_Util_String::getRandomStr(16);
		$data = $random . pack("N", strlen($text)) . $text . $appid;
		return $data;
	}

	/**
	 * 对编码后的密文进行解码 RawData = RandomStr(16) + DataLength(4) + Data(len) + PlatId(N);
	 *
	 * @param string $text 需要解码的密文
	 * @return array(string,string) array(解码后的明文, 合作平台appid)
	 */
	static public function decode($text)
	{
		$random = null;
		$data   = null;
		$appid  = null;

		// 去除16位随机字符串,网络字节序和AppId
		if (strlen($text) < 16) {
			return array($data, $appid);
		}

		$content  = substr($text, 16, strlen($text));
		$len_list = @unpack("N", substr($content, 0, 4));
		$data_len = $len_list[1];
		$data     = substr($content, 4, $data_len);
		$appid    = substr($content, 4 + $data_len);

		return array($data, $appid);
	}

}

