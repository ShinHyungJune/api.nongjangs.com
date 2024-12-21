<?php

namespace Database\Factories;

use App\Models\PhraseProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PhraseProductCategoryFactory extends Factory
{
    protected $model = PhraseProductCategory::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
        ];
    }
}
