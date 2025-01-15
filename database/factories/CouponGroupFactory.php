<?php

namespace Database\Factories;

use App\Enums\MomentCouponGroup;
use App\Enums\TargetCouponGroup;
use App\Enums\TypeCouponGroup;
use App\Enums\TypeDiscount;
use App\Enums\TypeExpire;
use App\Models\CouponGroup;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CouponGroupFactory extends Factory
{
    protected $model = CouponGroup::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'moment' => null,
            'type' => TypeCouponGroup::ALL,
            'type_package' => null,
            'all_product' => $this->faker->boolean(),
            'target' => TargetCouponGroup::ALL,
            'min_order' => 0,
            'type_discount' => TypeDiscount::NUMBER,
            'value' => $this->faker->randomNumber(),
            'max_price_discount' => $this->faker->randomNumber(),
            'min_price_order' => 0,
            'type_expire' => TypeExpire::FROM_DOWNLOAD,
            'started_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i'),
            'finished_at' => Carbon::now()->addDays(30)->format('Y-m-d H:i'),
            'days' => $this->faker->randomNumber(),

            'grade_id' => null,
        ];
    }
}
