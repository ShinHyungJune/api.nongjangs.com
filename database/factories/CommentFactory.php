<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\PresetProduct;
use App\Models\Prototype;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'description' => $this->faker->text(),

            'prototype_id' => Prototype::factory(),
            'preset_product_id' => PresetProduct::factory(),
        ];
    }
}
