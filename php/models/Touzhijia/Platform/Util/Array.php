<?php


class Touzhijia_Platform_Util_Array
{
	static public function print_rr($v)
	{
		echo "<pre>";
		print_r($v);
		echo "</pre>";
	}


	static public function isAllKeyExistsInArray($arr1, $arr2)
	{
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
