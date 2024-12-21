<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class SocialPlatform
{
    const GOOGLE = 1;
    const APPLE = 2;
    const KAKAO  = 3;
    const NAVER  = 4;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::GOOGLE => "구글",
            self::APPLE => "애플",
            self::KAKAO => "카카오",
            self::NAVER => "네이버",
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [

        ];
    }
}
