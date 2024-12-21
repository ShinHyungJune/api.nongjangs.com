<?php

namespace Database\Factories;

use App\Enums\TypeUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => TypeUser::COMMON,
            'point' => 0,
            'ids' => $this->faker->title,
            'email' => $this->faker->title,
            'name' => $this->faker->title,
            'contact' => $this->faker->title,
            'address' => $this->faker->title,
            'address_detail' => $this->faker->title,
            'address_zipcode' => $this->faker->title,
            'password' => $this->faker->title,
        ];
    }
}
