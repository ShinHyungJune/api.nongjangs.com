<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeBanner
{
    const MAIN = 1;
    const FARM_STORY = 2;
    const SUBSCRIBE = 3;
    const DYNAMIC = 4;

    public static function getValues()
    {
        return [
            self::MAIN,
            self::FARM_STORY,
            self::SUBSCRIBE,
            self::DYNAMIC,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::MAIN => "메인",
            self::FARM_STORY => "농가이야기",
            self::SUBSCRIBE => "정기구독",
            self::DYNAMIC => "직거래장터",
            "" => "",
        ];

        return $items[$value];
    }

}
