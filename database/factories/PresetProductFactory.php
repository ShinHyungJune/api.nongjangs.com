<?php

namespace Database\Factories;

use App\Enums\StatePresetProduct;
use App\Enums\TypePackage;
use App\Models\Coupon;
use App\Models\DeliveryCompany;
use App\Models\Option;
use App\Models\Package;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PresetProductFactory extends Factory
{
    protected $model = PresetProduct::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $package = Package::inRandomOrder()->first() ?? Package::factory()->create();
        $option = $product->options()->inRandomOrder()->first() ?? Option::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'state' => StatePresetProduct::BEFORE_PAYMENT,

            'product_title' => $product->title,
            'product_price' => $product->price,
            'product_price_origin' => $product->price_origin,
            'count' => 1,
            'option_title' => $option->title,
            'option_price' => $option->price,
            'option_type' => $option->type,

            'package_active' => rand(0,1),
            'package_name' => "야채꾸러미",
            'package_count' => $package->count,
            'package_price' => $package->price_single,
            'package_type' => TypePackage::SINGLE,

            'preset_id' => Preset::factory(),
            'package_id' => $package->id,
            'product_id' => $product->id,
            'option_id' => $option->id,

            'delivery_name' => $this->faker->name,
            'delivery_address' => $this->faker->address,
            'delivery_address_detail' => $this->faker->name,
            'delivery_address_zipcode' => "20120",
            'delivery_requirement' => '배송요청사항예시',
            'delivery_company_id' => DeliveryCompany::inRandomOrder()->first()->id ?? DeliveryCompany::factory()->create()->id,

            'reason_request_cancel' => '이래서 취소했어요',
            'reason_deny_cancel' => '이래서 취소요청해요.',
            'alert_pack' => 0,
        ];
    }
}
