<?php

namespace Database\Factories;

use App\Models\Count;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CountFactory extends Factory
{
    protected $model = Count::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'sum_weight' => $this->faker->randomNumber(),
            'sum_store' => $this->faker->randomNumber(),
        ];
    }
}
