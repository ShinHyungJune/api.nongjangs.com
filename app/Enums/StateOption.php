<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StateOption
{
    const ONGOING = 1;
    const HIDE  = 2;
    const EMPTY  = 3;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::ONGOING => "판매중",
            self::HIDE => "숨김",
            self::EMPTY => "품절",
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::ONGOING,
            self::HIDE,
            self::EMPTY,
        ];
    }
}
