<?php

/**
 * 提供基于PKCS7算法的数据补全算法
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Security
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:40:36
 */
class Touzhijia_Platform_Security_Pkcs7Encoder
{
	/**
	 * 对需要加密的明文进行填充补位
	 *
	 * @param string $text 需要进行填充补位操作的明文
	 * @param ingeter $block_size 补位长度
	 * @return string 补齐明文字符串
	 */
	static public function encode($text, $block_size = 32)
	{
 		//计算需要填充的位数
		$text_length = strlen($text);
		$amount_to_pad = $block_size - ($text_length % $block_size);

		//获得补位所用的字符
		$pad_chr = chr($amount_to_pad);
		$text .= str_repeat($pad_chr, $amount_to_pad);
	
		return $text;
	}

	/**
	 * 对解密后的明文去除补位符
	 *
	 * @param string $text 待去除补位符的字符串
	 * @return string 删除填充补位后的字符串
	 */
	static public function decode($text)
	{
		$pad = ord(substr($text, -1));
		$text = substr($text, 0, -$pad);
		return $text;
	}

}
