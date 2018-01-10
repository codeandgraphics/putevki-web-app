<?php

namespace Utils;

class Morpher
{
	private static $url = 'https://ws3.morpher.ru/russian/declension?s=';

	public static function cases($text)
	{
		$response = file_get_contents(self::$url . $text);

		if (!$response) return false;

		$cases = (array) new \SimpleXMLElement($response);

		$result = new \stdClass();

		$result->name_rod = $cases['Р'];
		$result->name_dat = $cases['Д'];
		$result->name_vin = $cases['В'];
		$result->name_tvo = $cases['Т'];
		$result->name_pre = $cases['П'];

		return $result;
	}
}