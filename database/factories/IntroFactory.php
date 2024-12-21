<?php

namespace Database\Factories;

use App\Models\Intro;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IntroFactory extends Factory
{
    protected $model = Intro::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'use' => $this->faker->boolean(),
        ];
    }
}
