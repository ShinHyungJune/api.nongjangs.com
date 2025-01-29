<?php

namespace Database\Factories;

use App\Enums\TypePointHistory;
use App\Models\Order;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PointHistoryFactory extends Factory
{
    protected $model = PointHistory::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => TypePointHistory::ORDER_CREATED,
            'increase' => $this->faker->boolean(),
            'point' => $this->faker->randomNumber(),
            'point_leave' => $this->faker->randomNumber(),
            'user_id' => User::factory(),
        ];
    }
}
