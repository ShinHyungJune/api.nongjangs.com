<?php

namespace Database\Factories;

use App\Enums\TypePackage;
use App\Models\Card;
use App\Models\Delivery;
use App\Models\Package;
use App\Models\PackageSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PackageSettingFactory extends Factory
{
    protected $model = PackageSetting::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => null,
            'type_package' => TypePackage::BUNGLE,
            'term_week' => $this->faker->randomNumber(),
            'active' => $this->faker->boolean(),
            'will_order_at' => Carbon::now(),
            'retry' => $this->faker->randomNumber(),

            'user_id' => User::factory(),
            'card_id' => Card::factory(),
            'delivery_id' => Delivery::factory(),
            'first_package_id' => Package::factory(),
        ];
    }
}
