<?php

namespace Database\Factories;

use App\Models\Bookmark;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookmarkFactory extends Factory
{
    protected $model = Bookmark::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'bookmarkable_id' => $this->faker->word(),
            'bookmarkable_type' => $this->faker->word(),

            'user_id' => User::factory(),
        ];
    }
}
