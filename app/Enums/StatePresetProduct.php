<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class StatePresetProduct
{
    const BEFORE_PAYMENT = 1;
    const READY = 2;
    const ONGOING_DELIVERY = 3;
    const DELIVERED = 4;
    const CONFIRMED = 5;
    const CANCEL = 6;
    const ONGOING_REFUND = 7;
    const FINISH_REFUND = 8;
    const DENY_REFUND = 9;

    const CONFIRM_PROTOTYPE  = 12;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::BEFORE_PAYMENT => "결제전",
            self::READY => "상품준비중",
            self::ONGOING_DELIVERY => "배송중",
            self::DELIVERED => "배송완료",
            self::CONFIRMED => "구매확정",
            self::CANCEL => "취소",
            self::ONGOING_REFUND => "교환/반품 진행중",
            self::FINISH_REFUND => "교환/반품 완료",
            self::DENY_REFUND => "교환/반품 반려",

        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::BEFORE_PAYMENT,
            self::READY,
            self::CONFIRM_PROTOTYPE,
            self::ONGOING_DELIVERY,
            self::DELIVERED,
            self::CONFIRMED,
            self::CANCEL,
            self::ONGOING_REFUND,
            self::FINISH_REFUND,
            self::DENY_REFUND,
        ];
    }
}
