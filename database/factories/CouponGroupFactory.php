<?php

namespace Database\Factories;

use App\Models\CouponGroup;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CouponGroupFactory extends Factory
{
    protected $model = CouponGroup::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'moment' => $this->faker->randomNumber(),
            'type' => $this->faker->randomNumber(),
            'type_package' => $this->faker->randomNumber(),
            'all_product' => $this->faker->boolean(),
            'target' => $this->faker->randomNumber(),
            'min_order' => $this->faker->randomNumber(),
            'type_discount' => $this->faker->randomNumber(),
            'value' => $this->faker->randomNumber(),
            'max_price_discount' => $this->faker->randomNumber(),
            'min_price_order' => $this->faker->randomNumber(),
            'type_expire' => $this->faker->randomNumber(),
            'started_at' => Carbon::now(),
            'finished_at' => Carbon::now(),
            'days' => $this->faker->randomNumber(),

            'grade_id' => Grade::factory(),
        ];
    }
}
