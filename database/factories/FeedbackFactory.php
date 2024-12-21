<?php

namespace Database\Factories;

use App\Models\Feedback;
use App\Models\Preset;
use App\Models\PresetProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FeedbackFactory extends Factory
{
    protected $model = Feedback::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'admin' => $this->faker->boolean(),
            'description' => $this->faker->text(),

            'preset_product_id' => PresetProduct::factory(),
        ];
    }
}
