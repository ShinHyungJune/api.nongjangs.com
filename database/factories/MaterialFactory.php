<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => $this->faker->word(),
            'title' => $this->faker->word(),
            'descriptions' => "['123', '222']",

            'category_id' => Category::factory(),
        ];
    }
}
