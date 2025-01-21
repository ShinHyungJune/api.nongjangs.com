<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypePackageMaterial
{
    const SINGLE = 1;
    const BUNGLE = 2;
    const CAN_SELECT = 3;

    public static function getValues()
    {
        return [
            self::SINGLE,
            self::BUNGLE,
            self::CAN_SELECT,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::SINGLE => "싱글",
            self::BUNGLE => "벙글",
            self::CAN_SELECT => "선택가능",
            "" => "",
        ];

        return $items[$value];
    }

}
