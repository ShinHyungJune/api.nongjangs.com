<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SizeFactory extends Factory
{
    protected $model = Size::class;

    public function definition()
    {
        $titles = ['소 (10cm * 10cm)', '중 (50cm * 50cm)', '대 (100cm * 100cm)'];

        $product = Product::inRandomOrder()->first();

        if(!$product)
            $product = Product::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $titles[rand(0, count($titles) - 1)],
            'price' => rand(500, 1000),
            'open' => 1,

            'product_id' => $product->id,
        ];
    }
}
