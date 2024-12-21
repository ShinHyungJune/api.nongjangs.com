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
    const USER_CREATED = 1;
    const ORDER_SUCCESS = 2;
    const PROTOTYPE_CREATED = 3;

    const PRESET_PRODUCT_START_DELIVERY = 4;
    const PRESET_PRODUCT_DIRECT_DELIVERY_REQUIRED = 5;
    const PRESET_PRODUCT_PROTOTYPE_REQUIRED = 6;
    const QNA_CREATED = 7;
    const QNA_ANSWERED = 8;
    const FEEDBACK_CREATED = 9;
    const ESTIMATE_CREATED = 10;
    const REVIEW_REQUIRED = 11;
    const ORDER_CREATED_BY_VBANK = 12;

    public static function getTemplateId($type)
    {
        $items = [
            self::USER_CREATED => "KA01TP241115052358786rqkRLeqEFwn",
            self::ORDER_SUCCESS => "KA01TP241127061617973bh0FBQMCy4g",
            self::PROTOTYPE_CREATED => "KA01TP241120103425139csgm2VjyBUw",
            self::PRESET_PRODUCT_START_DELIVERY => "KA01TP241127060548877LKMogrb29Yp",
            self::PRESET_PRODUCT_DIRECT_DELIVERY_REQUIRED => "KA01TP241115054604868P0tCkNzGv5S",
            self::PRESET_PRODUCT_PROTOTYPE_REQUIRED => "KA01TP241115053353668haqui3FyIzz",
            self::QNA_CREATED => "KA01TP241115053205240Xw8Pk9rfH5D",
            self::QNA_ANSWERED => "KA01TP241115053048769xAnX8sB2CVw",
            self::FEEDBACK_CREATED => "KA01TP241115053612082TUh0d8QGWBr",
            self::ESTIMATE_CREATED => "KA01TP241115053959446C4J1OW3u6Zl",
            self::REVIEW_REQUIRED => "KA01TP241115054438508RVeBeESOxdZ",
            self::ORDER_CREATED_BY_VBANK => "KA01TP241125050922825TsX6QfWTFuO",
            "" => "",
        ];

        return $items[$type];
    }

    public static function getLabel($value)
    {
        $items = [
            self::USER_CREATED => "회원가입",
            self::ORDER_SUCCESS => "결제완료",
            self::PROTOTYPE_CREATED => "시안생성",
            self::PRESET_PRODUCT_START_DELIVERY => "배송시작",
            self::PRESET_PRODUCT_DIRECT_DELIVERY_REQUIRED => "방문수령, 퀵서비스 제작완료 안내",
            self::PRESET_PRODUCT_PROTOTYPE_REQUIRED => "시안요청",
            self::QNA_CREATED => "문의생성",
            self::QNA_ANSWERED => "문의답변완료",
            self::FEEDBACK_CREATED => "피드백생성",
            self::ESTIMATE_CREATED => "견적생성",
            self::REVIEW_REQUIRED => "리뷰작성요청",
            self::ORDER_CREATED_BY_VBANK => "가상계좌결제",
            "" => "",
        ];

        return $items[$value];
    }
}
