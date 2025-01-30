<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeAlarm
{
    const ORDER_SUCCESS = 1;

    const PRESET_PRODUCT_START_DELIVERY = 2;
    const QNA_ANSWERED = 3;
    const REVIEW_REQUIRED = 4;
    const ORDER_CREATED_BY_VBANK = 5;
    const ALERT_PACKAGE_START_PACK = 6;

    public static function getTemplateId($type)
    {
        $items = [
            self::ORDER_SUCCESS => "KA01TP241127061617973bh0FBQMCy4g",
            self::PRESET_PRODUCT_START_DELIVERY => "KA01TP241127060548877LKMogrb29Yp",
            self::QNA_ANSWERED => "KA01TP241115053048769xAnX8sB2CVw",
            self::REVIEW_REQUIRED => "KA01TP241115054438508RVeBeESOxdZ",
            self::ORDER_CREATED_BY_VBANK => "KA01TP241125050922825TsX6QfWTFuO",
            self::ALERT_PACKAGE_START_PACK => "KA01TP241125050922825TsX6QfWTFuO",
            "" => "",
        ];

        return $items[$type];
    }

    public static function getLabel($value)
    {
        $items = [
            self::ORDER_SUCCESS => "결제완료",
            self::PRESET_PRODUCT_START_DELIVERY => "배송시작",
            self::QNA_ANSWERED => "문의답변완료",
            self::REVIEW_REQUIRED => "리뷰작성요청",
            self::ORDER_CREATED_BY_VBANK => "가상계좌결제",
            self::ALERT_PACKAGE_START_PACK => "꾸러미 품목구성알림",
            "" => "",
        ];

        return $items[$value];
    }
}
