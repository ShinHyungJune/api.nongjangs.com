<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeDeliveryPrice
{
    const STATIC  = 1;
    const CONDITIONAL  = 2;
    const PRICE_BY_COUNT  = 3;

    public static function getValues()
    {
        return [
            self::STATIC,
            self::CONDITIONAL,
            self::PRICE_BY_COUNT,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::STATIC => "고정배송비",
            self::CONDITIONAL => "조건부 무료배송",
            self::PRICE_BY_COUNT => "수량별 차등 배송비",
            "" => "",
        ];

        return $items[$value];
    }

}
