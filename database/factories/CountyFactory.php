<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\County;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CountyFactory extends Factory
{
    protected $model = County::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'order' => $this->faker->randomNumber(),

            'city_id' => City::factory(),
        ];
    }
}
