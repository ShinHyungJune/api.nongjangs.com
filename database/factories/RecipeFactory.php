<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition(): array
    {
        $titles = [
            "된장찌개",
            "김치찌개",
            "불고기",
            "삼겹살 구이",
            "김치볶음밥",
            "계란말이",
            "잡채",
            "해물파전",
            "비빔밥",
            "갈비찜",
            "볶음밥",
            "순두부찌개",
            "떡볶이",
            "오징어볶음",
            "닭갈비"
        ];

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

        $materials = [
            json_encode([
                ["title" => "된장", "count" => "2T"],
                ["title" => "두부", "count" => "1모"],
                ["title" => "양파", "count" => "1개"],
                ["title" => "애호박", "count" => "1/2개"],
                ["title" => "버섯", "count" => "50g"],
                ["title" => "대파", "count" => "1대"]
            ]),
            json_encode([
                ["title" => "김치", "count" => "200g"],
                ["title" => "돼지고기", "count" => "150g"],
                ["title" => "두부", "count" => "1/2모"],
                ["title" => "양파", "count" => "1개"],
                ["title" => "대파", "count" => "1대"],
                ["title" => "청양고추", "count" => "1개"]
            ]),
            json_encode([
                ["title" => "소고기", "count" => "150g"],
                ["title" => "양파", "count" => "1개"],
                ["title" => "대파", "count" => "1대"],
                ["title" => "마늘", "count" => "1T"],
                ["title" => "간장", "count" => "2T"],
                ["title" => "설탕", "count" => "1T"],
                ["title" => "참기름", "count" => "1T"]
            ]),
            json_encode([
                ["title" => "삼겹살", "count" => "200g"],
                ["title" => "상추", "count" => "5장"],
                ["title" => "쌈장", "count" => "2T"],
                ["title" => "마늘", "count" => "3쪽"],
                ["title" => "고추", "count" => "1개"]
            ]),
            json_encode([
                ["title" => "김치", "count" => "150g"],
                ["title" => "밥", "count" => "1공기"],
                ["title" => "참기름", "count" => "1T"],
                ["title" => "계란", "count" => "1개"],
                ["title" => "대파", "count" => "1대"],
                ["title" => "간장", "count" => "1T"]
            ]),
        ];

        $seasonings = [
            json_encode([
                ["title" => "된장", "count" => "2T"],
                ["title" => "고춧가루", "count" => "1T"],
                ["title" => "마늘", "count" => "1T"],
                ["title" => "간장", "count" => "1T"]
            ]),
            json_encode([
                ["title" => "고춧가루", "count" => "1T"],
                ["title" => "된장", "count" => "1T"],
                ["title" => "마늘", "count" => "1T"],
                ["title" => "간장", "count" => "1T"]
            ]),
            json_encode([
                ["title" => "간장", "count" => "2T"],
                ["title" => "설탕", "count" => "1T"],
                ["title" => "참기름", "count" => "1T"],
                ["title" => "마늘", "count" => "1T"]
            ]),
            json_encode([
                ["title" => "소금", "count" => "1/2t"],
                ["title" => "후추", "count" => "약간"],
                ["title" => "참기름", "count" => "1T"]
            ]),
            json_encode([
                ["title" => "간장", "count" => "1T"],
                ["title" => "참기름", "count" => "1T"],
                ["title" => "설탕", "count" => "1/2T"],
                ["title" => "후추", "count" => "약간"]
            ]),
        ];

        $ways = [
            json_encode(["된장과 물을 끓여서 두부, 양파, 애호박, 버섯을 넣고 끓입니다.", "대파를 넣고 마무리합니다."]),
            json_encode(["돼지고기를 볶고 김치와 두부를 넣어 끓입니다.", "양파와 대파를 넣고 끓여줍니다."]),
            json_encode(["고기를 양념에 재워둔 후 팬에 구워줍니다.", "구운 고기를 채소와 함께 섞어서 먹습니다."]),
            json_encode(["삼겹살을 구워 상추에 싸고, 마늘과 고추를 곁들여 먹습니다."]),
            json_encode(["김치와 밥을 넣고 볶습니다.", "참기름과 대파로 마무리합니다."]),
            json_encode(["계란을 풀어 프라이팬에 부친 후 당근과 대파를 넣고 볶습니다."]),
            json_encode(["당면을 삶고, 고기와 채소를 볶습니다.", "모든 재료를 섞고 양념을 넣어 볶습니다."]),
            json_encode(["해물과 채소를 부침가루에 묻혀 팬에 굽습니다.", "바삭해지면 꺼내서 먹습니다."]),
            json_encode(["밥에 나물과 고기를 올리고 고추장과 참기름을 넣어 비빕니다."]),
            json_encode(["갈비를 양념에 재운 후, 채소를 넣고 함께 끓입니다.", "감자가 익을 때까지 끓입니다."]),
            json_encode(["남은 밥에 채소와 양념을 넣고 볶습니다.", "계란을 풀어 함께 볶아줍니다."]),
            json_encode(["순두부를 끓인 후, 고춧가루와 마늘로 양념합니다.", "두부와 함께 끓입니다."]),
            json_encode(["떡과 어묵을 고추장에 볶고, 양파와 대파를 넣습니다."]),
            json_encode(["오징어와 채소를 고추장 양념에 볶습니다.", "맛있게 볶아져서 완성됩니다."]),
            json_encode(["닭고기를 양념에 재운 후, 양배추와 함께 볶습니다.", "매운 양념이 스며들도록 볶아줍니다."])
        ];

        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $titles[rand(0, count($titles) - 1)],
            'description' => $descriptions[rand(0, count($descriptions) - 1)],
            'materials' => $materials[rand(0, count($materials) - 1)],
            'seasonings' => $seasonings[rand(0, count($seasonings) - 1)],
            'ways' => $ways[rand(0, count($ways) - 1)],

            'user_id' => $user->id,
        ];
    }
}
