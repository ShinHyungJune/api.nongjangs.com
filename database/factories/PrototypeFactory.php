<?php

namespace Database\Factories;

use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Prototype;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PrototypeFactory extends Factory
{
    protected $model = Prototype::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'preset_product_id' => PresetProduct::factory(),
            'confirmed' => 0,
        ];
    }
}
