<?php

namespace Database\Factories;

use App\Models\Factory;
use App\Models\FarmStory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Support\Carbon;

class FarmStoryFactory extends BaseFactory
{
    protected $model = FarmStory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'count_view' => $this->faker->randomNumber(),

            'user_id' => User::factory(),
            'factory_id' => Factory::factory(),
        ];
    }
}
