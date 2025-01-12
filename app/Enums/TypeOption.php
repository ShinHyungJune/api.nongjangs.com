<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeOption
{
    const REQUIRED  = 1;
    const ADDITIONAL   = 2;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::REQUIRED => "필수",
            self::ADDITIONAL => "추가",
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::REQUIRED,
            self::ADDITIONAL,
        ];
    }
}
