<?php


namespace Unit;


class Stringunit
{
    public static function notEmpty($string, $length)
    {
        if (empty($string)) {
            return false;
        }
        if (strlen($string) != $length) {
            return false;
        }
        return true;
    }

    public static function stingMetch($pattern,$string){
        return preg_match($pattern,$string);
    }
}