<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StateCouponGroup
{
    const WAIT = 1;
    const ONGOING = 2;
    const FINISH  = 3;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::WAIT => "진행전",
            self::ONGOING => "진행중",
            self::FINISH => "종료",
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::WAIT,
            self::ONGOING,
            self::FINISH,
        ];
    }
}
