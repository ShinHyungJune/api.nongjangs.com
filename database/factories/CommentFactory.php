<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'commentable_id' => $this->faker->word(),
            'commentable_type' => $this->faker->word(),
            'description' => $this->faker->text(),

            'user_id' => User::factory(),
        ];
    }
}
