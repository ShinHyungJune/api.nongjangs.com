<?php

namespace Database\Factories;

use App\Models\QnaCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QnaCategoryFactory extends Factory
{
    protected $model = QnaCategory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
        ];
    }
}
