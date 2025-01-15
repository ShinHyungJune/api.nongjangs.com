<?php

namespace Database\Factories;

use App\Enums\StateOption;
use App\Enums\TypeOption;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OptionFactory extends Factory
{
    protected $model = Option::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            'product_id' => $product->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => TypeOption::REQUIRED,
            'state' => StateOption::ONGOING,
            'title' => $this->faker->word(),
            'price' => $this->faker->randomNumber(),
            'count' => $this->faker->randomNumber(),
        ];
    }
}
