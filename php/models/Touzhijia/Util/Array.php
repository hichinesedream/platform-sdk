<?php

/**
 * 数组相关功能
 * 
 * @category   Touzhijia
 * @package    Touzhijia_Util
 * @author     JamesQin <qinwq@touzhijia.com>
 * @copyright  (c) 2014-2016 Touzhijia Financial Information Ltd. Inc. (http://www.touzhijia.com)
 * @version    1.0.0 2016-03-30 16:09:39
 */
class Touzhijia_Util_Array
{
	static public function print_rr($v)
	{
		echo "<pre>";
		print_r($v);
		echo "</pre>";
	}


	static public function isAllKeyExistsInArray($arr1, $arr2)
	{
		if (!is_array($arr1) || !is_array($arr2)) {
			return false;
		}

		foreach ($arr1 as $k => $v) {
			if (!array_key_exists($k, $arr2)) {
				return false;
			}

			if (is_array($v)) {
				return self::isAllKeyExistsInArray($arr1[$k], $arr2[$k]);
			}
		}

		return true;
	}

}
