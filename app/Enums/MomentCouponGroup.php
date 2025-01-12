<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class MomentCouponGroup
{
    const UPDATE_PROFILE = 1;
    const GRADE = 2;
    const BIRTHDAY = 3;
    const FIRST_ORDER = 4;
    public static function getValues()
    {
        return [
            self::UPDATE_PROFILE,
            self::GRADE,
            self::BIRTHDAY,
            self::FIRST_ORDER,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::UPDATE_PROFILE => "프로필 업데이트",
            self::GRADE => "등급혜택",
            self::BIRTHDAY => "생일쿠폰",
            self::FIRST_ORDER => "첫구매",
            "" => "",
        ];

        return $items[$value];
    }

}
