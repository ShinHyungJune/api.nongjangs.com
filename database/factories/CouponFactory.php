<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
            'coupon_group_id' => CouponGroup::factory(),
            'order_id' => Order::factory(),
        ];
    }
}
