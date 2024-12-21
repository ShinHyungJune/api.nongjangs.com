<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeDelivery
{
    const DELIVERY = 1;
    const QUICK = 2;
    const DIRECT = 3;

    public static function getValues()
    {
        return [
            self::DELIVERY,
            self::QUICK,
            self::DIRECT,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::DELIVERY => "택배",
            self::QUICK => "퀵서비스",
            self::DIRECT => "직접방문",
            "" => "",
        ];

        return $items[$value];
    }

}
