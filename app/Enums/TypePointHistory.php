<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypePointHistory
{
    const USER_RECOMMENDED = 1;
    const USER_RECOMMEND = 2;
    const ORDER_CREATED = 3;

    public static function getValues()
    {
        return [
            self::USER_RECOMMENDED,
            self::USER_RECOMMEND,
            self::ORDER_CREATED,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::USER_RECOMMENDED => "추천인 추천받음",
            self::USER_RECOMMEND => "추천인 추천함",
            self::ORDER_CREATED => "주문",
            "" => "",
        ];

        return $items[$value];
    }

}
