<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StateFeedback
{
    const WAIT = 1;
    const FINISH  = 2;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::WAIT => "대기",
            self::FINISH => "처리완료",
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::WAIT,
            self::FINISH,
        ];
    }
}
