<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeRequest
{
    const WINNER = 1;
    const GOLF = 2;
    const TROPHY = 3;
    const ROYALTY = 4;

    public static function getValues()
    {
        return [
            self::WINNER,
            self::GOLF,
            self::TROPHY,
            self::ROYALTY,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::WINNER => "상패",
            self::GOLF => "골프패",
            self::TROPHY => "트로피",
            self::ROYALTY => "명패/기타",
            "" => "",
        ];

        return $items[$value];
    }

}
