<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TargetCouponGroup
{
    const ALL = 1;
    const GRADE = 2;
    const ORDER_HISTORY = 3;
    const PERSONAL = 4;

    public static function getValues()
    {
        return [
            self::ALL,
            self::GRADE,
            self::ORDER_HISTORY,
            self::PERSONAL,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::ALL => "전체회원",
            self::GRADE => "고객등급",
            self::ORDER_HISTORY => "구매이력",
            self::PERSONAL => "개인회원",
            "" => "",
        ];

        return $items[$value];
    }

}
