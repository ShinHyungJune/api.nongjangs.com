<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\RecommendCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RecommendCategoryFactory extends Factory
{
    protected $model = RecommendCategory::class;

    public function definition()
    {
        $category = Category::inRandomOrder()->first();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'subtitle' => $this->faker->word(),

            'category_id' => $category ? $category->id : Category::factory()->create()->id,
        ];
    }
}
