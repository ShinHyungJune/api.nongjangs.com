<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeCouponGroup
{
    const ALL = 1;
    const PACKAGE = 2;
    const PRODUCT = 3;
    const DELIVERY = 4;
    public static function getValues()
    {
        return [
            self::ALL,
            self::PACKAGE,
            self::PRODUCT,
            self::DELIVERY,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::ALL => "모든상품",
            self::PACKAGE => "꾸러미",
            self::PRODUCT => "직거래상품",
            self::DELIVERY => "배송비",
            "" => "",
        ];

        return $items[$value];
    }

}
