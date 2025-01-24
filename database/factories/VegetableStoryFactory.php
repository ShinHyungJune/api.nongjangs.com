<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PresetProduct;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\User;
use App\Models\VegetableStory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VegetableStoryFactory extends Factory
{
    protected $model = VegetableStory::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $presetProduct = PresetProduct::inRandomOrder()->first() ?? PresetProduct::factory()->create();
        $recipe = Recipe::inRandomOrder()->first() ?? Recipe::factory()->create();

        $descriptions = [
            "된장찌개는 고소한 된장 맛과 다양한 채소가 어우러져 깊은 맛을 자랑하는 한국의 대표적인 찌개입니다.",
            "김치찌개는 신김치와 돼지고기, 두부 등을 넣어 끓인 한국의 전통적인 찌개입니다.",
            "불고기는 얇게 썬 소고기를 양념에 재운 후 구워 먹는 맛있는 한국식 바비큐 요리입니다.",
            "삼겹살 구이는 바삭하게 구운 삼겹살에 쌈채소와 함께 먹는 인기 있는 고기 요리입니다.",
            "김치볶음밥은 매운 김치와 밥을 함께 볶아 간단하게 만들 수 있는 맛있는 한 끼입니다.",
            "계란말이는 부드럽고 고소한 계란으로 만든 반찬으로, 밥반찬으로 좋습니다.",
            "잡채는 당면과 다양한 채소, 고기를 볶아 만든 고소하고 쫄깃한 음식입니다.",
            "해물파전은 다양한 해산물과 채소를 넣어 만든 바삭한 전입니다.",
            "비빔밥은 다양한 채소와 고기를 비벼 먹는 한국의 대표적인 혼합밥입니다.",
            "갈비찜은 소갈비와 다양한 채소를 함께 조려 만든 고기찜 요리입니다.",
            "볶음밥은 남은 밥으로 간단히 만들 수 있는 맛있는 한 그릇 요리입니다.",
            "순두부찌개는 부드러운 순두부와 고춧가루로 매운 맛을 낸 찌개입니다.",
            "떡볶이는 떡과 어묵을 고추장 양념에 볶아 만든 매콤한 간식입니다.",
            "오징어볶음은 오징어와 채소를 고추장 양념에 볶아 만든 매운 요리입니다.",
            "닭갈비는 닭고기와 각종 야채를 매운 양념에 볶아 만든 인기 있는 요리입니다."
        ];

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'description' => $descriptions[rand(0, count($descriptions) - 1)],

            'user_id' => $user->id,
            'package_id' => $presetProduct->package_id,
            'product_id' => $presetProduct->product_id,
            'preset_product_id' => $presetProduct->id,
            'recipe_id' => $recipe->id,
        ];
    }
}
