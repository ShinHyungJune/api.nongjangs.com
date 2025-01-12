<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class TypeMaterial
{
    const PRODUCT = 1;
    const PACKAGE = 2;

    public static function getLabel($value)
    {
        $items = [
            '' => '',
            self::PRODUCT => "직거래 상품",
            self::PACKAGE => "패키지 구독상품",
        ];

        return $items[$value];
    }

    public static function getValues()
    {
        return [
            self::PRODUCT,
            self::PACKAGE,
        ];
    }
}
