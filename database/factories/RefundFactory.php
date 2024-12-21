<?php

namespace Database\Factories;

use App\Enums\StateRefund;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefundFactory extends Factory
{
    protected $model = Refund::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'preset_product_id' => PresetProduct::factory(),
            'category' => $this->faker->title,
            'title' => $this->faker->title,
            'description' => $this->faker->title,
            'state' => StateRefund::WAIT,
        ];
    }
}
