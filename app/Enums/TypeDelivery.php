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
    const FREE = 1;
    const EACH = 2;
    public static function getValues()
    {
        return [
            self::FREE,
            self::EACH,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::FREE => "무료배송",
            self::EACH => "개별배송",
            "" => "",
        ];

        return $items[$value];
    }

}
