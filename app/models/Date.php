<?php

namespace Models;

class Date
{
    const DB_DATE_FORMAT = 'Y-m-d';
    const DATE_FORMAT = 'd.m.Y';

    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';
    const DATETIME_FORMAT = 'd.m.Y H:i:s';

    public static function toDbDate($value)
    {
        $date = \DateTime::createFromFormat(self::DATE_FORMAT, $value);
        if ($date) {
            return $date->format(self::DB_DATE_FORMAT);
        }
        return false;
    }

    public static function fromDbDate($value)
    {
        $date = \DateTime::createFromFormat(self::DB_DATE_FORMAT, $value);
        if ($date) {
            return $date->format(self::DATE_FORMAT);
        }
        return false;
    }

    public static function toDbDateTime($value)
    {
        $date = \DateTime::createFromFormat(self::DATETIME_FORMAT, $value);
        if ($date) {
            return $date->format(self::DB_DATETIME_FORMAT);
        }
        return false;
    }

    public static function fromDbDateTime($value)
    {
        $date = \DateTime::createFromFormat(self::DB_DATETIME_FORMAT, $value);
        if ($date) {
            return $date->format(self::DATETIME_FORMAT);
        }
        return false;
    }

    public static function currentDbDateTime()
    {
        $date = new \DateTime();
        return $date->format(self::DB_DATETIME_FORMAT);
    }

    public static function dbDateMonthAgo()
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P1M'));
        return $date->format(self::DB_DATETIME_FORMAT);
    }
}
