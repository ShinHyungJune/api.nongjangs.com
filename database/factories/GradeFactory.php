<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'level' => rand(1,6),
            'title' => $this->faker->word(),
            'ratio_refund' => $this->faker->randomFloat(),
            'min_price' => $this->faker->randomNumber(),
            'min_count_package' => 10,
        ];
    }
}
