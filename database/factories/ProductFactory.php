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
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        $farm = Farm::inRandomOrder()->first() ?? Farm::factory()->create();

        $titles = [
            "신선한 제철 과일 모음전",
            "농장에서 바로 배송된 아삭한 채소",
            "달콤한 딸기 한 박스",
            "산지 직송 국산 사과",
            "비타민 가득한 신선한 오렌지",
            "샐러드용 아삭한 파프리카 세트",
            "영양만점 신선한 브로콜리 묶음",
            "햇감자 대량 구매 특가",
            "싱싱한 냉이와 달래 모음",
            "촉촉하고 달달한 수박",
            "당일 수확 신선한 쌈채소",
            "잘 익은 바나나",
            "달콤한 포도 한 송이",
            "요리 필수 재료 국산 양파",
            "부드럽고 고소한 아보카도",
            "달콤한 향이 가득한 복숭아",
            "유기농 감귤",
            "신선함이 살아있는 여름 배추",
            "다용도로 활용 가능한 애호박",
            "씨 없는 감 대량 구매 특가"
        ];

        $price = rand(5000,100000);

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'state' => StateProduct::ONGOING,
            'uuid' => $this->faker->uuid(),
            'title' => $titles[rand(0, count($titles) - 1)],
            'price' => $price,
            'price_origin' => $price + rand(1000, 10000),
            'need_tax' => $this->faker->boolean(),
            'can_use_coupon' => 1,
            'can_use_point' => 1,
            'type_delivery' => TypeDelivery::FREE,
            'delivery_company' => DeliveryCompany::CJ,
            'type_delivery_price' => TypeDeliveryPrice::STATIC,
            'price_delivery' => 3000,
            'prices_delivery' => "[]",
            'min_price_for_free_delivery_price' => rand(50000, 100000),
            'can_delivery_far_place' => $this->faker->boolean(),
            // 'delivery_price_far_place' => $this->faker->randomNumber(),
            'delivery_company_refund' => $this->faker->randomNumber(),
            'delivery_price_refund' => $this->faker->randomNumber(),
            'delivery_address_refund' => $this->faker->address(),
            'description' => "
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/1.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/2.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/3.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/4.png' />
                <br/>
                <img src='https://api.nongjangs.com/images/%EA%B7%A4/5.png' />
                <br/>
                ",

            'category_id' => $category->id,
            'farm_id' => $farm->id,
            'city_id' => $farm->county->city_id,
            'county_id' => $farm->county_id,
        ];
    }
}
