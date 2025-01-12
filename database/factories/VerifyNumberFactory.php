<?php

namespace Database\Factories;

use App\Models\VerifyNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VerifyNumberFactory extends Factory
{
    protected $model = VerifyNumber::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'ids' => $this->faker->uuid,
            'number' => $this->faker->uuid,
            'verified' => 0,
        ];
    }
}
