<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypePointHistory
{
    const USER_RECOMMENDED = 1;
    const USER_RECOMMEND = 2;
    const ORDER_CREATED = 3;
    const PHOTO_REVIEW_CREATED = 4;
    const TEXT_REVIEW_CREATED = 5;
    const BEST_REVIEW_UPDATED = 6;

    public static function getValues()
    {
        return [
            self::USER_RECOMMENDED,
            self::USER_RECOMMEND,
            self::ORDER_CREATED,
            self::PHOTO_REVIEW_CREATED,
            self::TEXT_REVIEW_CREATED,
            self::BEST_REVIEW_UPDATED,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::USER_RECOMMENDED => "추천인 추천받음",
            self::USER_RECOMMEND => "추천인 추천함",
            self::ORDER_CREATED => "주문",
            self::PHOTO_REVIEW_CREATED => "포토리뷰 작성",
            self::TEXT_REVIEW_CREATED => "일반리뷰 작성",
            self::BEST_REVIEW_UPDATED => "베스트리뷰 선정",
            
            "" => "",
        ];

        return $items[$value];
    }

}
