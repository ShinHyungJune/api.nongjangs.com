<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypePackage
{
    const SINGLE = 1;
    const BUNGLE = 2;

    public static function getValues()
    {
        return [
            self::SINGLE,
            self::BUNGLE,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::SINGLE => "싱글",
            self::BUNGLE => "벙글",
            "" => "",
        ];

        return $items[$value];
    }

}
