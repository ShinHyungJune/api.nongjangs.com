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
    const ORDER_CREATED = 1;
    const ORDER_CANCLED = 2;
    const USER_CREATED = 3;
    const PRESET_PRODUCT_CONFIRMED = 4;
    const ADMIN_GIVE = 5;

    public static function getValues()
    {
        return [
            self::ORDER_CREATED,
            self::ORDER_CANCLED,
            self::USER_CREATED,
            self::PRESET_PRODUCT_CONFIRMED,
            self::ADMIN_GIVE,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::ORDER_CREATED => "주문",
            self::ORDER_CANCLED => "주문취소",
            self::USER_CREATED => "회원가입 포인트",
            self::PRESET_PRODUCT_CONFIRMED => "구매확정",
            self::ADMIN_GIVE => "관리자 부여",
            "" => "",
        ];

        return $items[$value];
    }

}
