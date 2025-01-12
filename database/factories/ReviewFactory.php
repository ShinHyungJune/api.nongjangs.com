<?php

namespace Database\Factories;

use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'best' => $this->faker->boolean(),
            'point' => $this->faker->randomNumber(),
            'title' => $this->faker->text(),
            'description' => $this->faker->text(),
            'score' => rand(0,5),

            'user_id' => User::factory(),
            // 'preset_product_id' => PresetProduct::factory(),
            'product_id' => Product::factory(),
            'photo' => 0,
        ];
    }
}
