<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeProduct
{
    const PROCESS = 0;
    const NEW = 1;
    const EXPORT = 2;
    const FIND = 3;

    public static function getValues()
    {
        return [
            self::PROCESS,
            self::NEW,
            self::EXPORT,
            self::FIND,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::PROCESS => "가공제품",
            self::NEW => "회원사 신개발품",
            self::EXPORT => "수출 희망 아이템",
            self::FIND => "투자자를 찾습니다",
            "" => "",
        ];

        return $items[$value];
    }

}
