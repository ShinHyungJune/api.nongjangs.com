<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeCouponHistory
{
    const ORDER_CREATED = 1;
    const ORDER_CANCLED = 2;
    const CREATED = 3;

    public static function getValues()
    {
        return [
            self::ORDER_CREATED,
            self::ORDER_CANCLED,
            self::CREATED,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::ORDER_CREATED => "주문",
            self::ORDER_CANCLED => "주문취소",
            self::CREATED => "쿠폰발급",
            "" => "",
        ];

        return $items[$value];
    }

}
