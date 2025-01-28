<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StatePackage
{
    const WAIT_PACK = 1;
    const ONGOING_PACK = 2;
    const DELIVERY_READY = 3;
    const WILL_OUT = 4;

    public static function getValues()
    {
        return [
            self::WAIT_PACK,
            self::ONGOING_PACK,
            self::DELIVERY_READY,
            self::WILL_OUT,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::WAIT_PACK => "구성대기",
            self::ONGOING_PACK => "품목구성",
            self::DELIVERY_READY => "배송준비",
            self::WILL_OUT => "출고예정",
            "" => "",
        ];

        return $items[$value];
    }

}
