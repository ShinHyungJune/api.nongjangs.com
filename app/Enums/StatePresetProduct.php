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
    const ONGOING_PROTOTYPE  = 3;
    const FINISH_PROTOTYPE  = 4;
    const ONGOING_DELIVERY = 5;
    const DELIVERED = 6;
    const CONFIRMED = 7;
    const CANCEL = 8;
    const ONGOING_REFUND = 9;
    const FINISH_REFUND = 10;
    const DENY_REFUND = 11;

    const CONFIRM_PROTOTYPE  = 12;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::BEFORE_PAYMENT => "결제전",
            self::READY => "상품준비중",
            self::ONGOING_PROTOTYPE => "시안준비중",
            self::FINISH_PROTOTYPE => "시안제작완료",
            self::ONGOING_DELIVERY => "배송중",
            self::DELIVERED => "배송완료",
            self::CONFIRMED => "구매확정",
            self::CANCEL => "취소",
            self::ONGOING_REFUND => "교환/반품 진행중",
            self::FINISH_REFUND => "교환/반품 완료",
            self::DENY_REFUND => "교환/반품 반려",
            self::CONFIRM_PROTOTYPE => "상품제작중", // 시안확정
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::BEFORE_PAYMENT,
            self::READY,
            self::ONGOING_PROTOTYPE,
            self::FINISH_PROTOTYPE,
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
