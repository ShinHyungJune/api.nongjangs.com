<?php

namespace Database\Seeders;

use App\Enums\TypeBanner;
use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'type' => TypeBanner::MAIN,
                'tag' => '트로피',
                'title' => '
                빛나는 순간을 더욱 빛나게 하는
오직 하나뿐인 디자인을 그려냅니다.',
                'description' => '장인의 상패에서는 노력해 온 순간과 행복을 나눈 시간을 
오래도록 추억할 수 있도록 기록합니다.',
                'url' => '/products?category_id=2',
                'img' => '/images/banner1.png',
            ],
            [
                'type' => TypeBanner::MAIN,
                'tag' => '감사패',
                'title' => '
                감사패 테스트 빛나는 순간을 더욱 빛나게 하는
오직 하나뿐인 디자인을 그려냅니다.',
                'description' => '장인의 상패에서는 노력해 온 순간과 행복을 나눈 시간을 
오래도록 추억할 수 있도록 기록합니다.',
                'url' => '/products?category_id=1',
                'img' => '/images/banner1.png',
            ],
            [
                'type' => TypeBanner::MIDDLE,
                'title' => '문구추천',
                'url' => '/phrases',
                'img' => '/images/middleBanner1.png',
            ],
            [
                'type' => TypeBanner::MIDDLE,
                'title' => '당일제작',
                'url' => '/products',
                'img' => '/images/middleBanner2.png',
            ],
            [
                'type' => TypeBanner::BAND,
                'title' => '가성비 상패',
                'url' => '/products',
                'img' => '/images/bandBanner1.png',
            ],
        ];

        foreach($items as $item){
            $createdItem = Banner::create(\Illuminate\Support\Arr::except($item, 'img'));

            if(config("app.env") != 'local'){
                $createdItem->addMedia(public_path($item['img']))->preservingOriginal()->toMediaCollection("img", "s3");
            }
        }
    }
}
