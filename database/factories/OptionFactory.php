<?php

namespace Database\Factories;

use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OptionFactory extends Factory
{
    protected $model = Option::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => $this->faker->randomNumber(),
            'state' => $this->faker->randomNumber(),
            'title' => $this->faker->word(),
            'price' => $this->faker->randomNumber(),
            'count' => $this->faker->randomNumber(),
        ];
    }
}
