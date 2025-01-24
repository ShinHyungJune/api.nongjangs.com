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
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $presetProduct = PresetProduct::inRandomOrder()->first() ?? PresetProduct::factory()->create();
        $recipe = Recipe::inRandomOrder()->first() ?? Recipe::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'description' => $this->faker->text(),

            'user_id' => $user->id,
            'package_id' => $presetProduct->package_id,
            'product_id' => $presetProduct->product_id,
            'preset_product_id' => $presetProduct->id,
            'recipe_id' => $recipe->id,
        ];
    }
}
