<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypePackageChangeHistory
{
    const FAST = 1;
    const LATE = 2;

    public static function getValues()
    {
        return [
            self::FAST,
            self::LATE,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::FAST => "당기기",
            self::LATE => "미루기",
            "" => "",
        ];

        return $items[$value];
    }

}
