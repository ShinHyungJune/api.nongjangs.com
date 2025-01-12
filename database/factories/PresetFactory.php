<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PresetFactory extends Factory
{
    protected $model = Preset::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'price_delivery' => $this->faker->randomNumber(),
            'price' => $this->faker->randomNumber(),
            'price_total' => $this->faker->randomNumber(),
            'price_discount' => $this->faker->randomNumber(),
            'count_option_required' => $this->faker->randomNumber(),
            'count_option_additional' => $this->faker->randomNumber(),

            'order_id' => Order::factory(),
            'cart_id' => Cart::factory(),
            'user_id' => User::factory(),
        ];
    }
}
