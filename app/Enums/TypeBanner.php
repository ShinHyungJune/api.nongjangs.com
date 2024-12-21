<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeBanner
{
    const MAIN = 1;
    const CATEGORY = 2;
    const MIDDLE = 3;
    const BAND = 4;

    public static function getValues()
    {
        return [
            self::MAIN,
            self::CATEGORY,
            self::MIDDLE,
            self::BAND,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::MAIN => "메인배너",
            self::CATEGORY => "상품 카테고리",
            self::MIDDLE => "중간배너",
            self::BAND => "띠배너",
            "" => "",
        ];

        return $items[$value];
    }

}
