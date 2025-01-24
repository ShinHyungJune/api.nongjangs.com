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
    const ORDER_CANCLED = 4;
    const PHOTO_REVIEW_CREATED = 5;
    const TEXT_REVIEW_CREATED = 6;
    const BEST_REVIEW_UPDATED = 7;
    const VEGETABLE_STORY_CREATED = 8;

    public static function getValues()
    {
        return [
            self::USER_RECOMMENDED,
            self::USER_RECOMMEND,
            self::ORDER_CREATED,
            self::ORDER_CANCLED,
            self::PHOTO_REVIEW_CREATED,
            self::TEXT_REVIEW_CREATED,
            self::BEST_REVIEW_UPDATED,
            self::VEGETABLE_STORY_CREATED,
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::USER_RECOMMENDED => "추천인 추천받음",
            self::USER_RECOMMEND => "추천인 추천함",
            self::ORDER_CREATED => "주문",
            self::ORDER_CANCLED => "주문취소",
            self::PHOTO_REVIEW_CREATED => "포토리뷰 작성",
            self::TEXT_REVIEW_CREATED => "일반리뷰 작성",
            self::BEST_REVIEW_UPDATED => "베스트리뷰 선정",
            self::VEGETABLE_STORY_CREATED => "채소이야기 작성",

            "" => "",
        ];

        return $items[$value];
    }

}
