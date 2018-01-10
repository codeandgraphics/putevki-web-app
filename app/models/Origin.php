<?php

namespace Models;

class Origin {
    const WEB = 1;
    const MOBILE = 2;
    const MOBILE_IOS = 3;
    const MOBILE_ANDROID = 4;

    /**
     * @param $value int
     * @return string
     */
    static public function toVerbal($value) {
        switch($value) {
            case self::WEB:
                return 'web';
            case self::MOBILE:
                return 'mobile';
            case self::MOBILE_IOS:
                return 'iOS';
            case self::MOBILE_ANDROID:
                return 'android';
            default:
                return 'web';
        }
    }
}