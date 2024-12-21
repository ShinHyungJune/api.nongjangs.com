<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StateOrder
{
    const BEFORE_PAYMENT = 1; // 결제준비
    const WAIT = 2; // 결제대기
    const SUCCESS = 3; // 결제성공
    const CANCEL = 4; // 주문취소

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::BEFORE_PAYMENT => "결제준비",
            self::WAIT => "결제대기",
            self::SUCCESS => "결제성공",
            self::CANCEL => "주문취소",
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [self::BEFORE_PAYMENT, self::WAIT, self::SUCCESS, self::CANCEL];
    }
}
