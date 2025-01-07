<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'materials' => $this->faker->word(),
            'seasonings' => $this->faker->word(),
            'ways' => $this->faker->word(),

            'user_id' => User::factory(),
        ];
    }
}
