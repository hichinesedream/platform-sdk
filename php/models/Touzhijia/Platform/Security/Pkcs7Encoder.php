<?php


/**
 * PKCS7Encoder class
 *
 * 提供基于PKCS7算法的加解密接口.
 */
class Touzhijia_Platform_Security_Pkcs7Encoder
{
	/**
	 * 对需要加密的明文进行填充补位
	 * @param $text 需要进行填充补位操作的明文
	 * @return 补齐明文字符串
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
	 * 对解密后的明文进行补位删除
	 * @param decrypted 解密后的明文
	 * @return 删除填充补位后的明文
	 */
	static public function decode($text)
	{
		$pad = ord(substr($text, -1));
		$text = substr($text, 0, -$pad);
		return $text;
	}

}
