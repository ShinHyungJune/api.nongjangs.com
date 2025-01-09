<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\FarmStory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Support\Carbon;

class FarmStoryFactory extends BaseFactory
{
    protected $model = FarmStory::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $farm = Farm::inRandomOrder()->first() ?? Farm::factory()->create();

        $titles = [
            "오늘도 싱싱한 당근을 수확했어요! 농장의 하루",
            "양파 수확 끝! 꿀팁 대방출 🍴",
            "복숭아향이 가득! 우리 농장의 수확 브이로그",
            "포도를 키우는 비법 공개! 🌱",
            "달콤한 딸기 한가득! 오늘의 수확기 🍓",
            "사과 따는 날! 농장에서 함께해요 🍎",
            "우리 농장의 배, 맛있게 익어가고 있어요 🍐",
            "싱그러운 샐러리가 가득! 오늘도 수확 완료 🥬",
            "싱싱한 상추로 샐러드 만들기! 농장에서 직접 수확한 재료 🌿",
            "뜨거운 햇살 아래 당근과 함께한 하루 🌞",
            "농장에서 만나는 특별한 양파 요리 레시피 🍽️",
            "복숭아 농사 일기: 달콤함을 키우는 비법 공개!",
            "우리 농장의 포도로 만든 홈메이드 주스 🍹",
            "농장에서 딸기를 직접 따는 재미 🍓",
            "사과로 가득 찬 과수원, 함께 구경해요!",
            "오늘도 배 농장에서 행복한 하루 🍐",
            "샐러리와 함께하는 싱그러운 농장 투어 🌱",
            "상추를 수확하고 맛있게 즐기는 법!",
            "싱싱한 당근을 가득 담았어요! 농장의 소소한 일상",
            "농장의 보물, 양파가 주렁주렁!",
            "오늘의 특별한 수확물, 바로 복숭아! 🍑",
            "싱그러운 포도로 농장을 채웠어요 🍇",
            "농장에서 키운 딸기, 정말 달콤해요 🍓",
            "사과 농사를 짓는 즐거움 🍎",
            "배 수확 작업 현장 스케치!",
            "싱그러운 샐러리를 요리로 만나는 방법 🥬",
            "상추로 하루를 마무리하는 농장의 모습 🌿",
            "오늘의 농장 이야기: 당근과의 특별한 하루",
            "양파 키우기, 우리가 농장에서 배운 것들",
            "복숭아 향 가득한 농장의 하루를 함께하세요!"
        ];

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $titles[rand(0, count($titles) - 1)],
            'description' => $this->faker->text(),
            'count_view' => $this->faker->randomNumber(),

            'user_id' => $user->id,
            'farm_id' => $farm->id,
        ];
    }
}
