<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\User;
use App\Models\VegetableStory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VegetableStoryFactory extends Factory
{
    protected $model = VegetableStory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'description' => $this->faker->text(),

            'user_id' => User::factory(),
            'package_id' => Package::factory(),
            'product_id' => Product::factory(),
            'preset_product_id' => PresetProduct::factory(),
            'recipe_id' => Recipe::factory(),
        ];
    }
}
