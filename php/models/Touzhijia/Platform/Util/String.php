<?php


class Touzhijia_Platform_Util_String
{
	static public function print_rr($v)
	{
		echo "<pre>";
		print_r($v);
		echo "</pre>";
	}


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
