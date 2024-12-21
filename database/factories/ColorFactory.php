<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ColorFactory extends Factory
{
    protected $model = Color::class;

    public function definition()
    {
        $titles = ['블랙', '화이트', '블루', '레드'];

        $product = Product::inRandomOrder()->first();

        if(!$product)
            $product = Product::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $titles[rand(0, count($titles) - 1)],
            'open' => 1,

            'product_id' => $product->id,
        ];
    }
}
