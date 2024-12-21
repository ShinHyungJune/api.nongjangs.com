<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StateQna
{
    const WAIT = 1;
    const FINISH = 2;

    public static function getValues()
    {
        return [
            self::WAIT,
            self::FINISH,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::WAIT => "답변대기",
            self::FINISH => "답변완료",
            "" => "",
        ];

        return $items[$value];
    }

}
