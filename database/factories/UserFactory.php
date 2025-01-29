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
            'email' => $this->faker->title,
            'name' => $this->faker->title,
            'contact' => $this->faker->title,

            'agree_promotion' => 0,

            'password' => $this->faker->title,
        ];
    }
}
