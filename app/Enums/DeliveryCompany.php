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
    const LOTTE = "1"; // 롯데택배
    const LOGEN = "2"; // 로젠택배
    const CJ = "3"; // CJ
    const POST = "4"; //

    public static function getOptions()
    {
        return [
            [
                'label' => DeliveryCompany::getLabel(DeliveryCompany::LOTTE),
                'value' => DeliveryCompany::LOTTE,
            ],
            /*[
                'label' => DeliveryCompany::getLabel(DeliveryCompany::LOGEN),
                'value' => DeliveryCompany::LOGEN,
            ],
            [
                'label' => DeliveryCompany::getLabel(DeliveryCompany::CJ),
                'value' => DeliveryCompany::CJ,
            ],*/
            [
                'label' => DeliveryCompany::getLabel(DeliveryCompany::POST),
                'value' => DeliveryCompany::POST,
            ],
        ];
    }

    public static function getLabel($value)
    {
        $items = [
            self::LOTTE => "롯데택배",
            self::LOGEN => "로젠택배",
            self::CJ => "CJ택배",
            self::POST => "우체국택배",
        ];

        return $items[$value];
    }
}
