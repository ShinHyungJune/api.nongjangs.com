<?php

namespace Database\Factories;

use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $descriptions = [
            "신선하고 아삭아삭한 식감이 정말 좋아요.",
            "달콤하면서도 적당히 신 맛이 매력적이에요.",
            "속이 꽉 차 있고 맛도 풍부해서 만족스러웠습니다.",
            "크기도 적당하고 싱싱해서 재구매할 의사가 있습니다.",
            "부드럽고 촉촉한 질감이 정말 마음에 들어요.",
            "자연 그대로의 신선함이 느껴져요.",
            "과즙이 풍부하고 먹기에도 편리했습니다.",
            "아삭하면서도 달콤한 맛이 요리에 딱이에요.",
            "향이 너무 좋고 신선함이 오래 유지돼요.",
            "선명한 색감과 신선한 상태가 인상적이었습니다.",
            "달콤하고 부드러운 식감이 일품이에요.",
            "껍질도 얇고 속도 꽉 차 있어서 아주 좋아요.",
            "고소하면서도 상큼한 맛이 정말 매력적입니다.",
            "신선도가 아주 뛰어나서 만족스러웠습니다.",
            "단단하면서도 적당히 부드러운 상태가 좋았어요.",
            "냉장고에 오래 보관해도 신선함이 유지되었어요.",
            "촉촉하고 달콤한 맛이 계속 생각납니다.",
            "아삭아삭한 식감이 샐러드에 잘 어울렸어요.",
            "맛과 식감 모두 훌륭해서 자주 구매할 것 같아요.",
            "생으로 먹어도 좋고 요리에 활용하기에도 딱입니다."
        ];

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'best' => $this->faker->boolean(),
            'point' => 50,
            'description' => $descriptions[rand(0, count($descriptions) - 1)],
            'score' => rand(0,5),

            'user_id' => $user->id,
            'preset_product_id' => PresetProduct::factory(),
            // 'product_id' => Product::factory(),
            'photo' => 0,
        ];
    }
}
