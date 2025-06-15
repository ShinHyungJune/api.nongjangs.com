<?php

namespace Database\Seeders;

use App\Enums\DeliveryCompany;
use App\Models\DeliveryCompany as DeliveryCompanyModel;
use Illuminate\Database\Seeder;

class DeliveryCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create delivery companies based on the enum values
        DeliveryCompanyModel::create([
            'title' => DeliveryCompany::getLabel(DeliveryCompany::CJ),
            'code' => DeliveryCompany::getId(DeliveryCompany::CJ),
        ]);

        DeliveryCompanyModel::create([
            'title' => DeliveryCompany::getLabel(DeliveryCompany::LOTTE),
            'code' => DeliveryCompany::getId(DeliveryCompany::LOTTE),
        ]);

        DeliveryCompanyModel::create([
            'title' => DeliveryCompany::getLabel(DeliveryCompany::POST),
            'code' => DeliveryCompany::getId(DeliveryCompany::POST),
        ]);

        DeliveryCompanyModel::create([
            'title' => DeliveryCompany::getLabel(DeliveryCompany::HANJIN),
            'code' => DeliveryCompany::getId(DeliveryCompany::HANJIN),
        ]);

        DeliveryCompanyModel::create([
            'title' => DeliveryCompany::getLabel(DeliveryCompany::LOGEN),
            'code' => DeliveryCompany::getId(DeliveryCompany::LOGEN),
        ]);

        DeliveryCompanyModel::create([
            'title' => DeliveryCompany::getLabel(DeliveryCompany::KYEONG_DONG),
            'code' => DeliveryCompany::getId(DeliveryCompany::KYEONG_DONG),
        ]);
    }
}