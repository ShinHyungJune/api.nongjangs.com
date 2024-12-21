<?php

namespace Database\Factories;

use App\Models\Qna;
use App\Models\QnaCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QnaFactory extends Factory
{
    protected $model = Qna::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        $qnaCategory = QnaCategory::inRandomOrder()->first();

        if(!$qnaCategory)
            $qnaCategory = QnaCategory::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'description' => $this->faker->word(),
            'qna_category_id' => $qnaCategory->id,
            'user_id' => $user->id,
        ];

    }
}
