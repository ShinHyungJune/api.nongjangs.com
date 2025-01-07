<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeTag
{
    const FARM_STORY  = 1;
    const RECIPE  = 2;
    const VEGETABLE_STORY  = 3;
    const PRODUCT   = 4;
    const PACKAGE   = 5;

    public static function getValues()
    {
        return [
            self::FARM_STORY,
            self::RECIPE,
            self::VEGETABLE_STORY,
            self::PRODUCT ,
            self::PACKAGE,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::FARM_STORY => "농가이야기",
            self::RECIPE => "레시피",
            self::VEGETABLE_STORY => "채소이야기",
            self::PRODUCT => "직거래장터",
            self::PACKAGE => "꾸러미",
            "" => "",
        ];

        return $items[$value];
    }

}
