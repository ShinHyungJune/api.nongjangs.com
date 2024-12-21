<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StateRefund
{
    const WAIT = 1;
    const SUCCESS = 2;
    const DENY = 3;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::WAIT => "대기",
            self::SUCCESS => "승인",
            self::DENY => "반려",
        ];

        return $items[$value];
    }

}
