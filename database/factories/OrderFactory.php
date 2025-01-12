<?php

namespace Database\Factories;

use App\Enums\StateOrder;
use App\Enums\TypeDelivery;
use App\Models\Order;
use App\Models\PayMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $payMethod = PayMethod::inRandomOrder()->first();

        return [
            'imp_uid' => $this->faker->unique()->uuid,
            'merchant_uid' => $this->faker->unique()->uuid,
            'user_id' => $user ? $user->id : User::factory()->create()->id,
            'user_name' => $this->faker->userName(),
            'user_email' => $this->faker->unique()->safeEmail(),
            'user_contact' => $this->faker->word(),
            'pay_method_id' => $payMethod ? $payMethod->id : PayMethod::factory()->create()->id,
            'pay_method_name' => $this->faker->name(),
            'pay_method_pg' => $this->faker->word(),
            'pay_method_method' => $this->faker->word(),
            'vbank_num' => $this->faker->word(),
            'vbank_name' => $this->faker->name(),
            'vbank_date' => $this->faker->word(),
            'buyer_name' => $this->faker->name(),
            'buyer_contact' => $this->faker->word(),
            'delivery_name' => $this->faker->name(),
            'delivery_contact' => $this->faker->word(),
            'delivery_address' => $this->faker->address(),
            'delivery_address_detail' => $this->faker->address(),
            'delivery_address_zipcode' => $this->faker->address(),
            'delivery_requirement' => $this->faker->word(),
            'point_use' => $this->faker->randomNumber(),
            'price' => $this->faker->randomNumber(),
            'state' => StateOrder::BEFORE_PAYMENT,
            'memo' => $this->faker->word(),
            'reason' => $this->faker->word(),
            'process_success' => 0,
            'process_record' => 0,
            'refund_owner' => $this->faker->word(),
            'refund_bank' => $this->faker->word(),
            'refund_account' => $this->faker->word(),
            'reason_refund' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
