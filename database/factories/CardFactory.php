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
            'card_number' => $this->faker->word(),
            'expiry' => $this->faker->word(),
            'birth' => $this->faker->word(),
            'password' => bcrypt($this->faker->password()),
            'name' => $this->faker->name(),
            'billingKey' => $this->faker->word(),

            'user_id' => User::factory(),
        ];
    }
}
