<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeExpire
{
    const SPECIFIC = 1;
    const FROM_DOWNLOAD = 2;
    public static function getValues()
    {
        return [
            self::SPECIFIC,
            self::FROM_DOWNLOAD,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::SPECIFIC => "날짜지정",
            self::FROM_DOWNLOAD => "다운로드일 기준",
            "" => "",
        ];

        return $items[$value];
    }

}
