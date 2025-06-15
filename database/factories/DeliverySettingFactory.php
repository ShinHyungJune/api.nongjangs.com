<?php

namespace Database\Factories;

use App\Enums\DeliveryCompany;
use App\Models\DeliverySetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DeliverySettingFactory extends Factory
{
    protected $model = DeliverySetting::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type_delivery' => $this->faker->randomNumber(),
            'delivery_company_id' => DeliveryCompany::inRandomOrder()->first()->id ?? DeliveryCompany::factory()->create()->id,
            'type_delivery_price' => $this->faker->randomNumber(),
            'price_delivery' => $this->faker->randomNumber(),
            'prices_delivery' => $this->faker->word(),
            'min_price_for_free_delivery_price' => $this->faker->randomNumber(),
            'can_delivery_far_place' => $this->faker->boolean(),
            // 'delivery_price_far_place' => $this->faker->randomNumber(),
        ];
    }
}
