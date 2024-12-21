<?php

namespace Database\Factories;

use App\Models\Logo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LogoFactory extends Factory
{
    protected $model = Logo::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
        ];
    }
}
