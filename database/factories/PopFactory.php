<?php

namespace Database\Factories;

use App\Models\Pop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PopFactory extends Factory
{
    protected $model = Pop::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'url' => $this->faker->url(),
            'open' => $this->faker->boolean(),
            'started_at' => Carbon::now(),
            'finished_at' => Carbon::now(),
        ];
    }
}