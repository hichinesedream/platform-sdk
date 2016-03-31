<?php

/**
 * 字符串处理相关功能
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Platform_Util
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:10:05
 */
class Touzhijia_Platform_Util_String
{

	/**
	 * 十六进制形式返回字符串
	 *
	 * @param string $string
	 * @return string
	 */
	static public function strhex($string) 
	{

	   $hex = '';
	   $len = strlen($string);
	   
	   for ($i = 0; $i < $len; $i++) {
	       
	       $hex .= str_pad(dechex(ord($string[$i])), 3, ' ', STR_PAD_LEFT);
	   
	   }
	       
	   return $hex;	   
	}
	

	/**
	 * 随机生成字符串
	 *
	 * @param integer $num 随机字符串长度
	 * @return string 生成的字符串
	 */
	static public function getRandomStr($num = 16)
	{

		$str = "";
		$str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($str_pol) - 1;
		for ($i = 0; $i < $num; $i++) {
			$str .= $str_pol[mt_rand(0, $max)];
		}
		
		return $str;
	}	
	
}
