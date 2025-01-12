<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'started_at' => Carbon::now(),
            'finished_at' => Carbon::now(),
            'count_goal' => $this->faker->randomNumber(),
            'count_participate' => $this->faker->randomNumber(),

            'product_id' => Product::factory(),
        ];
    }
}
