<?php

namespace Utils;

class Morpher
{
    private static $url = 'https://ws3.morpher.ru/russian/declension?s=';

    public static function cases($text)
    {
        $response = file_get_contents(self::$url . $text);

        if (!$response) {
            return false;
        }

        $cases = (array) new \SimpleXMLElement($response);

        $result = new \stdClass();

        $result->nameRod = $cases['Р'];
        $result->nameDat = $cases['Д'];
        $result->nameVin = $cases['В'];
        $result->nameTvo = $cases['Т'];
        $result->namePre = $cases['П'];

        return $result;
    }
}
