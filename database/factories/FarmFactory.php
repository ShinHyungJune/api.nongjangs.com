<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\County;
use App\Models\Farm;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Support\Carbon;

class FarmFactory extends BaseFactory
{
    protected $model = Farm::class;

    public function definition(): array
    {
        $titles = [
            '햇살가득 농원', '푸른들 농장', '맑은하늘 팜', '들꽃이 피는 농장', '한울타리 농원',
            '바람숲 농장', '초록빛 들녘', '정다운 농원', '산골마을 팜', '들녘사랑 농장',
            '달빛 품은 농원', '푸른초원 농장', '숲속향기 팜', '비단들 농원', '황금들녘 농장',
            '소담한 팜', '별빛내리는 농원', '바람길 농장', '햇살 품은 들판', '고운들 농장',
            '들꽃향기 팜', '산들바람 농원', '맑은샘 농장', '초롱초롱 농원', '달맞이 농장',
            '구름숲 농원', '아침햇살 농장', '따스한 땅 농원', '푸른별 팜', '새싹내음 농장'
        ];

        $county = County::inRandomOrder()->first() ?? County::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $titles[rand(0, count($titles) - 1)],

            'city_id' => $county->city_id,
            'county_id' => $county->id,
        ];
    }
}
