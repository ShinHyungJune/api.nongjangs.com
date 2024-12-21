<?php

namespace Database\Factories;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'main' => $this->faker->boolean(),
            'name' => $this->faker->name(),
            'contact' => $this->faker->word(),
            'address' => $this->faker->address(),
            'address_detail' => $this->faker->address(),
            'address_zipcode' => $this->faker->address(),

            'user_id' => User::factory(),
        ];
    }
}
