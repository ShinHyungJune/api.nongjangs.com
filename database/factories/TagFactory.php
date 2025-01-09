<?php

namespace Database\Factories;

use App\Enums\TypeTag;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $titles = [
            '당근', '양파', '복숭아', '포도', '딸기', '사과', '배', '샐러리', '상추',
            '수박', '바나나', '토마토', '감자', '고구마', '호박', '파프리카', '양배추', '브로콜리',
            '청포도', '키위', '망고', '레몬', '자몽', '체리', '블루베리', '라임', '복분자', '유자', '아보카도', '파인애플'
        ];

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => TypeTag::FARM_STORY,
            'title' => $titles[rand(0, count($titles) - 1)],
            'color' => $this->faker->hexColor,
            'open' => $this->faker->boolean(),
            'order' => $this->faker->randomNumber(),
        ];
    }
}
