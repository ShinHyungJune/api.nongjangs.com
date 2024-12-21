<?php

namespace Database\Factories;

use App\Models\PayMethod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PayMethodFactory extends Factory
{
    protected $model = PayMethod::class;

    public function definition(): array
    {
        return [
            'pg' => $this->faker->word(),
            'method' => $this->faker->word(),
            'name' => $this->faker->name(),
            'commission' => $this->faker->randomNumber(),
            'used' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
