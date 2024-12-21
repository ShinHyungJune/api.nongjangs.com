<?php

namespace Database\Factories;

use App\Enums\TypeBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => TypeBanner::CATEGORY,
            'title' => $this->faker->title,
            'description' => $this->faker->title,
            'url' => $this->faker->url,
        ];
    }
}
