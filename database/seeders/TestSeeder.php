<?php

namespace Database\Seeders;

use App\Enums\DeliveryCompany;
use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Models\AdditionalProduct;
use App\Models\Category;
use App\Models\Color;
use App\Models\Comment;
use App\Models\CouponHistory;
use App\Models\Estimate;
use App\Models\Feedback;
use App\Models\Keyword;
use App\Models\Order;
use App\Models\Phrase;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Prototype;
use App\Models\RecommendCategory;
use App\Models\Size;
use App\Models\User;
use App\Models\임시\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    protected $imgs = [
        "/images/product1.jpg",
        "/images/product2.jpg",
        "/images/product3.jpg",
        "/images/product4.jpg",
        "/images/product5.jpg",
        "/images/product6.jpg",
        "/images/product7.jpg",
        "/images/product8.jpg",
        "/images/product9.jpg",
        "/images/product10.jpg",
        "/images/product11.jpg",
        "/images/product12.jpg",
        "/images/product13.jpg",
        "/images/product14.jpg",
        "/images/product15.jpg",
        "/images/product16.jpg",
        "/images/product17.jpg",
    ];

    protected $user;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::where('email')->update(['password' => Hash::make('test@naver.com')]);

        /*$products = Product::where('product_id', null)->latest()->cursor();

        $index = 1;

        foreach($products as $product){
            $product->update(['order'=> $index]);

            $index++;
        }*/
        // Phrase::factory()->count(15)->create();

        /*Refund::factory()->count(10)->create();

        $presetProducts = PresetProduct::get();
        foreach($presetProducts as $presetProduct){
            $presetProduct->delete();
        }

        $presetProduct = PresetProduct::factory()->create([
            'state' => StatePresetProduct::READY,
            'title' => null,
            'will_prototype_finished_at' => null,
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'state' => StatePresetProduct::READY,
            'title' => '제작대기',
            'will_prototype_finished_at' => null,
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'state' => StatePresetProduct::READY,
            'title' => '제작진행중',
            'will_prototype_finished_at' => Carbon::now(),
        ]);

        $presetProduct = PresetProduct::factory()->create([
            'state' => StatePresetProduct::READY,
            'title' => '제작완료',
            'will_prototype_finished_at' => Carbon::now(),
        ]);

        Prototype::factory()->create(['preset_product_id' => $presetProduct->id, 'confirmed' => 0]);

        $presetProduct = PresetProduct::factory()->create([
            'state' => StatePresetProduct::READY,
            'title' => '확정완료',
            'will_prototype_finished_at' => Carbon::now(),
        ]);

        Prototype::factory()->create(['preset_product_id' => $presetProduct->id, 'confirmed' => 1]);*/
    }

    public function createPrototypes()
    {
        $presetProducts = PresetProduct::where('state', StatePresetProduct::FINISH_PROTOTYPE)->cursor();

        foreach($presetProducts as $presetProduct){
            for($i =0; $i < rand(1,2); $i++){
                $prototype = Prototype::factory()->create([
                    'title' => '프로토타입',
                    'preset_product_id' => $presetProduct->id,
                ]);

                $prototype->addMedia(public_path($this->imgs[rand(0, count($this->imgs) - 1)]))->preservingOriginal()->toMediaCollection("img", "s3");

                Comment::factory()->count(2)->create([
                    'prototype_id' => $prototype->id,
                    'preset_product_id' => $presetProduct->id
                ]);

                Feedback::factory()->create([
                    'preset_product_id' => $presetProduct->id,
                    'admin' => 1,
                ]);

                Feedback::factory()->create([
                    'preset_product_id' => $presetProduct->id,
                    'admin' => 0,
                ]);
            }
        }
    }

    public function createOrders()
    {
        $waitOrders = Order::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'state' => StateOrder::WAIT,
        ]);

        $successOrders = Order::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'state' => StateOrder::SUCCESS,
        ]);

        $cancelOrders = Order::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'state' => StateOrder::CANCEL,
        ]);

        foreach($waitOrders as $order){
            $this->attachPresets($order);
        }
        foreach($successOrders as $order){
            $this->attachPresets($order);
        }
        foreach($cancelOrders as $order){
            $this->attachPresets($order);
        }
    }

    public function attachPresets(Order $order)
    {
        $products = Product::has('media')->where('product_id', null)->where(['open' => 1])->inRandomOrder()->take(2)->get();

        foreach($products as $product){
            $size = Size::factory()->create([
                'product_id' => $product->id,
            ]);
            $color = Color::factory()->create([
                'product_id' => $product->id,
            ]);
            $additionalProduct = Product::factory()->create([
               'product_id' => $product->id
            ]);
        }

        $preset = Preset::factory()->create([
            'user_id' => $this->user->id,
            'order_id' => $order->id,
            'count' => 1,
        ]);

        foreach($products as $product){
            $size = $product->sizes()->inRandomOrder()->first();
            $color = $product->colors()->inRandomOrder()->first();
            $additionalProduct = $product->products()->inRandomOrder()->first();

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::READY
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::ONGOING_PROTOTYPE
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::FINISH_PROTOTYPE
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::ONGOING_DELIVERY,
                'delivery_number' => '584641143570',
                'delivery_company' => DeliveryCompany::CJ,
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::DELIVERED,
                'delivery_number' => '584641143570',
                'delivery_company' => DeliveryCompany::CJ,
            ]);

            PresetProduct::factory()->create([
                'preset_id' => $preset->id,
                'product_id' => $product->id,
                'size_id' => $size->id,
                'color_id' => $color->id,
                'state' => StatePresetProduct::CONFIRMED,
                'delivery_number' => '584641143570',
                'delivery_company' => DeliveryCompany::CJ,
            ]);

            PresetProduct::factory()->create([
                'additional' => 1,
                'preset_id' => $preset->id,
                'product_id' => $additionalProduct->id,
            ]);
        }

    }

    public function createReviews()
    {

        PointHistory::factory()->count(30)->create([
            'user_id' => $this->user->id
        ]);

        CouponHistory::factory()->count(30)->create([
            'user_id' => $this->user->id
        ]);
    }

}
