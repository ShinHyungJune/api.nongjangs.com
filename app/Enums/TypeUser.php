<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeUser
{
    const COMMON = 1;
    const COMPANY = 2;

    public static function getValues()
    {
        return [
            self::COMMON,
            self::COMPANY,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::COMMON => "일반",
            self::COMPANY => "회사",
        ];

        return $items[$value];
    }

}
