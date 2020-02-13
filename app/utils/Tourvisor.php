<?php

namespace Utils;

class Tourvisor
{
    const LOGIN = 'putevki.travel';
    const PASS = 'otpuskk';
    const ENDPOINT = 'https://tourvisor.ru/xml/';

    public static function getMethod($path, $params)
    {
        $params['format'] = 'json';
        $params['authlogin'] = self::LOGIN;
        $params['authpass'] = self::PASS;

        $query = self::ENDPOINT . $path . '.php?' . http_build_query($params);

        return json_decode(file_get_contents($query));
    }
}
