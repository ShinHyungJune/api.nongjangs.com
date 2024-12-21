<?php

namespace Database\Factories;

use App\Models\Phrase;
use App\Models\PhraseProductCategory;
use App\Models\PhraseReceiverCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PhraseFactory extends Factory
{
    protected $model = Phrase::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'description' => $this->faker->text(),

            'phrase_product_category_id' => PhraseProductCategory::factory(),
            'phrase_receiver_category_id' => PhraseReceiverCategory::factory(),
        ];
    }
}
