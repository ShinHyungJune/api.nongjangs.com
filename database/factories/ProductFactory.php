<?php

namespace Database\Factories;

use App\Enums\DeliveryCompany;
use App\Enums\StateProduct;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use App\Models\Category;
use App\Models\City;
use App\Models\County;
use App\Models\Farm;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'state' => StateProduct::ONGOING,
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->word(),
            'price' => $this->faker->randomNumber(),
            'price_origin' => $this->faker->randomNumber(),
            'need_tax' => $this->faker->boolean(),
            'can_use_coupon' => $this->faker->boolean(),
            'can_use_point' => $this->faker->boolean(),
            'count' => $this->faker->randomNumber(),
            'type_delivery' => TypeDelivery::FREE,
            'delivery_company' => DeliveryCompany::CJ,
            'type_delivery_price' => TypeDeliveryPrice::STATIC,
            'price_delivery' => $this->faker->randomNumber(),
            'prices_delivery' => "[]",
            'min_price_for_free_delivery_price' => $this->faker->randomNumber(),
            'can_delivery_far_place' => $this->faker->boolean(),
            'delivery_price_far_place' => $this->faker->randomNumber(),
            'delivery_company_refund' => $this->faker->randomNumber(),
            'delivery_price_refund' => $this->faker->randomNumber(),
            'delivery_address_refund' => $this->faker->address(),
            'description' => $this->faker->text(),

            'category_id' => Category::factory(),
            'farm_id' => Farm::factory(),
            'city_id' => City::factory(),
            'county_id' => County::factory(),
        ];
    }
}
