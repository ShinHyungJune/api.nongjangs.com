<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Option;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PresetProductFactory extends Factory
{
    protected $model = PresetProduct::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'state' => $this->faker->randomNumber(),
            'product_title' => $this->faker->word(),
            'product_price' => $this->faker->randomNumber(),
            'product_price_origin' => $this->faker->randomNumber(),
            'count' => $this->faker->randomNumber(),
            'option_title' => $this->faker->word(),
            'option_price' => $this->faker->randomNumber(),
            'option_type' => $this->faker->randomNumber(),

            'preset_id' => Preset::factory(),
            'product_id' => Product::factory(),
            'option_id' => Option::factory(),
            'coupon_id' => Coupon::factory(),
        ];
    }
}
