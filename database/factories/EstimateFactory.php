<?php

namespace Database\Factories;

use App\Models\Estimate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EstimateFactory extends Factory
{
    protected $model = Estimate::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'contact' => $this->faker->word(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'budget' => $this->faker->randomNumber(),
            'count' => $this->faker->randomNumber(),
        ];
    }
}
