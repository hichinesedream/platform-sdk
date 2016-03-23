<?php


/**
 * Touzhijia_Platform_Protocol_DataEncoder class
 *
 * 提供接收和推送给投之家消息的消息编码接口.
 */
class Touzhijia_Platform_Protocol_DataEncoder
{
	/**
	 * 对明文进行格式化 RawData = RandomStr + DataLength + Data + PlatId;
	 * @param string $text 需要加密的明文
	 * @return string 格式化后的密文
	 */
	static public function encode($text, $appid)
	{
		//获得16位随机字符串，填充到明文之前
		$random = Touzhijia_Platform_Util_String::getRandomStr(16);
		$data = $random . pack("N", strlen($text)) . $text . $appid;
		return $data;
	}

	/**
	 * 对密文进行解密 RawData = RandomStr(16) + DataLength(4) + Data(len) + PlatId(N);
	 * @param string $encrypted 需要解密的密文
	 * @return string 解密得到的明文
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

