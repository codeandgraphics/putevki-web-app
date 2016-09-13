<?php

namespace Utils;

class Morpher
{

	static $url = 'http://api.morpher.ru/WebService.asmx/GetXml?s=';

	static function cases($text)
	{
		$response = file_get_contents(self::$url . $text);

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