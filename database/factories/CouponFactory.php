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

    public function definition()
    {
        $couponGroup = CouponGroup::inRandomOrder()->first();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->title,
            'ratio_discount' => $this->faker->randomNumber(),
            'will_finished_at' => Carbon::now()->addDays(30),

            'user_id' => User::factory(),
            'coupon_group_id' => $couponGroup ? $couponGroup->id : CouponGroup::factory()->create()->id,
            // 'order_id' => Order::factory(),
        ];
    }
}
