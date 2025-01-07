<?php

namespace Database\Factories;

use App\Enums\TypeBanner;
use Carbon\Carbon;
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
            'type' => TypeBanner::MAIN,
            'title' => $this->faker->title,
            'subtitle' => $this->faker->title,
            'url' => $this->faker->url,
            'button' => '바로가기',
            'color_text' => 'white',
            'color_button' => 'white',
            'started_at' => Carbon::now()->subDays(2),
            'finished_at' => Carbon::now()->addWeeks(8),
        ];
    }
}
