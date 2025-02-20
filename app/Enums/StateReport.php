<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StateReport
{
    const WAIT = 1;
    const FINISH = 2;
    const HIDE = 3;

    public static function getValues()
    {
        return [
            self::WAIT,
            self::FINISH,
            self::HIDE,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::WAIT => "미처리",
            self::FINISH => "정상",
            self::HIDE => "숨김",
            "" => "",
        ];

        return $items[$value];
    }

}
