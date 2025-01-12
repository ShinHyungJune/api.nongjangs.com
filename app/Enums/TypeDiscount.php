<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeDiscount
{
    const NUMBER = 1;
    const RATIO = 2;
    public static function getValues()
    {
        return [
            self::NUMBER,
            self::RATIO,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::NUMBER => "원",
            self::RATIO => "%",
            "" => "",
        ];

        return $items[$value];
    }

}
