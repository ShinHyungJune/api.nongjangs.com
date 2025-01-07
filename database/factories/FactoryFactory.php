<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\County;
use App\Models\Factory;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Support\Carbon;

class FactoryFactory extends BaseFactory
{
    protected $model = Factory::class;

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
