<?php

namespace Database\Factories;

use App\Models\CouponGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CouponGroupFactory extends Factory
{
    protected $model = CouponGroup::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'ratio_discount' => $this->faker->randomNumber(),
            'duration' => $this->faker->randomNumber(),
        ];
    }
}
