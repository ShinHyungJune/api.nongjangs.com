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
    const BEFORE_PAYMENT = 1; // 결제전
    const WAIT = 2; // 결제대기
    const READY = 3; // 배송준비
    const WILL_OUT = 4; // 출고예정
    const ONGOING_DELIVERY = 5; // 배송중
    const DELIVERED = 6;
    const CONFIRMED = 7;
    const CANCEL = 8;
    const REQUEST_CANCEL = 9;
    const DENY_CANCEL = 10;


    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::BEFORE_PAYMENT => "결제대기",
            self::WAIT => "결제대기",
            self::READY => "배송준비",
            self::WILL_OUT => "출고예정",
            self::ONGOING_DELIVERY => "배송중",
            self::DELIVERED => "배송완료",
            self::CONFIRMED => "구매확정",
            self::CANCEL => "주문취소",
            self::REQUEST_CANCEL => "취소요청",
            self::DENY_CANCEL => "취쇼요청반려",

        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::BEFORE_PAYMENT,
            self::WAIT,
            self::READY,
            self::WILL_OUT,
            self::ONGOING_DELIVERY,
            self::DELIVERED,
            self::CONFIRMED,
            self::CANCEL,
            self::REQUEST_CANCEL,
            self::DENY_CANCEL,
        ];
    }
}
