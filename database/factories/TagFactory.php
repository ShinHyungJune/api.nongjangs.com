<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => $this->faker->word(),
            'title' => $this->faker->word(),
            'color' => $this->faker->word(),
            'open' => $this->faker->boolean(),
            'order' => $this->faker->randomNumber(),
        ];
    }
}
