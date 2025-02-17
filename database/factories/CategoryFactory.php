<?php

namespace Database\Factories;

use App\Enums\TypeCategory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'order' => $this->faker->randomNumber(),
            'title' => $this->faker->word(),
            'type' => TypeCategory::PRODUCT,
        ];
    }
}
