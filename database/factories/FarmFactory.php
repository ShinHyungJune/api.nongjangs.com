<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\County;
use App\Models\Farm;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Support\Carbon;

class FarmFactory extends BaseFactory
{
    protected $model = Farm::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),

            'city_id' => City::factory(),
            'county_id' => County::factory(),
        ];
    }
}
