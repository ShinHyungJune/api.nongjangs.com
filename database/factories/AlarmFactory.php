<?php

namespace Database\Factories;

use App\Models\Alarm;
use App\Models\Estimate;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\Prototype;
use App\Models\Qna;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AlarmFactory extends Factory
{
    protected $model = Alarm::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => $this->faker->word(),
            'contact' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),

            'user_id' => User::factory(),
            'preset_product_id' => PresetProduct::factory(),
            'order_id' => Order::factory(),
            'preset_id' => Preset::factory(),
            'qna_id' => Qna::factory(),
            'prototype_id' => Prototype::factory(),
            'feedback_id' => Feedback::factory(),
            'estimate_id' => Estimate::factory(),
        ];
    }
}
