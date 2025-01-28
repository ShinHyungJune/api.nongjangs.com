<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackageChangeHistory;
use App\Models\PresetProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PackageChangeHistoryFactory extends Factory
{
    protected $model = PackageChangeHistory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => $this->faker->word(),

            'user_id' => User::factory(),
            'preset_product_id' => PresetProduct::factory(),
            'origin_package_id' => Package::factory(),
        ];
    }
}
