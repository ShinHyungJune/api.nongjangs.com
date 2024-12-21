<?php

namespace Database\Factories;

use App\Enums\TypeDelivery;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $price = rand(1000,10000);
        $priceDiscount = $price - rand(0,500);
        $priceOrigin = $price + $priceDiscount;
        $priceDelivery = 0;

        $category = Category::inRandomOrder()->first();

        return [
            // 'category_id' => $category ? $category->id : Category::factory()->create()->id,
            'open' => 1,
            'empty' => 0,
            'count_view' => $this->faker->randomNumber(),
            'count_order' => $this->faker->randomNumber(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'summary' => $this->faker->text(),
            'price' => $price,
            'price_discount' => $priceDiscount,
            'price_origin' => $priceOrigin,
            'price_delivery' => $priceDelivery,
            'pop' => $this->faker->boolean(),
            'special' => $this->faker->boolean(),
            'recommend' => $this->faker->boolean(),
            'duration' => '제작기간 예시',
            'texture' => '재질 예시',
            'type_delivery' => TypeDelivery::DELIVERY,
            'creator' => '제조사 예시',
            'case' => '케이스 예시',
            'way_to_create' => '작업방식 예시',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
