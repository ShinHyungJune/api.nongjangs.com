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
        ];

        return $items[$value];
    }
}
