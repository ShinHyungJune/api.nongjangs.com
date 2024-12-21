<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeRefund
{
    const EXCHANGE = 1;
    const REFUND = 2;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::EXCHANGE => "교환",
            self::REFUND => "환불",
        ];

        return $items[$value];
    }

}
