<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class DeliveryCompany
{
    const CJ = "1"; // CJ
    const POST = "2"; // 우체국
    const HANJIN = "3"; // 한진택배
    public static function getOptions()
    {
        return [
            [
                'label' => DeliveryCompany::getLabel(DeliveryCompany::CJ),
                'value' => DeliveryCompany::CJ,
            ],
            /*[
                'label' => DeliveryCompany::getLabel(DeliveryCompany::LOGEN),
                'value' => DeliveryCompany::LOGEN,
            ],
            [
                'label' => DeliveryCompany::getLabel(DeliveryCompany::CJ),
                'value' => DeliveryCompany::CJ,
            ],*/

        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::CJ => "CJ택배",
            self::POST => "우체국",
            self::HANJIN => "한진택배",
        ];

        return $items[$value];
    }

    public static function getId($value)
    {
        $items = [
            self::CJ => "kr.cjlogistics",
            self::POST => "kr.epost",
            self::HANJIN => "kr.hanjin",
        ];

        return $items[$value];
    }
}
