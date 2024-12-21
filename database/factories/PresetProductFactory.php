<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Prototype;
use App\Models\Review;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PresetProductFactory extends Factory
{
    protected $model = PresetProduct::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();

        if(!$product)
            $product = Product::factory()->create();

        $color = Color::inRandomOrder()->first();

        if(!$color)
            $color = Color::factory()->create();

        $size = Size::inRandomOrder()->first();

        if(!$size)
            $size = Size::factory()->create();

        return [
            'additional' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'count' => $this->faker->randomNumber(),
            'product_title' => $product->title,
            'price' => $product->price,
            'price_discount' => $product->price_discount,
            'price_origin' => $product->price_origin,
            'price_delivery' => $product->price_delivery,
            'size_title' => $size->title,
            'size_price' => $size->price,
            'color_title' => $color->title,

            'preset_id' => Preset::factory(),
            'product_id' => $product->id,
            'color_id' => $color->id,
            'size_id' => $size->id,
        ];
    }
}
