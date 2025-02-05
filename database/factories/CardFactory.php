<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'number' => $this->faker->word(),
            'expiry_month' => $this->faker->word(),
            'expiry_year' => $this->faker->word(),
            'birth_or_business_number' => $this->faker->word(),
            'password' => bcrypt($this->faker->password()),
            'name' => $this->faker->name(),
            'billing_key' => $this->faker->word(),

            'user_id' => User::factory(),
        ];
    }
}
