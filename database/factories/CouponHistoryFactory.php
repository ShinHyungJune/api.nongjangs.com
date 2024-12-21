<?php

namespace Database\Factories;

use App\Enums\TypeCouponHistory;
use App\Models\CouponHistory;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CouponHistoryFactory extends Factory
{
    protected $model = CouponHistory::class;

    public function definition()
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'type' => TypeCouponHistory::CREATED,
            'increase' => $this->faker->boolean(),
            'title' => $this->faker->word(),

            'user_id' => User::factory(),
            'order_id' => Order::factory(),
        ];
    }
}
