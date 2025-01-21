<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        $willDeliveryAt = Carbon::now()->startOfWeek()->addWeek()->addDays(4);

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'count' => $this->faker->randomNumber(),
            'tax' => $this->faker->boolean(),
            'start_pack_wait_at' => (clone $willDeliveryAt)->addDays(1)->setHour(0)->setMinutes(0),
            'finish_pack_wait_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(0)->setHour(16)->setMinutes(0),
            'start_pack_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(0)->setHour(16)->setMinutes(0),
            'finish_pack_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(1)->setHour(9)->setMinutes(0),
            'start_delivery_ready_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(1)->setHour(9)->setMinutes(0),
            'finish_delivery_ready_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(2)->setHour(18)->setMinutes(0),
            'start_will_out_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(2)->setHour(18)->setMinutes(0),
            'finish_will_out_at' => Carbon::now()->addWeek()->startOfWeek()->addDays(3)->setHour(18)->setMinutes(0),
            'will_delivery_at' => $willDeliveryAt,
        ];
    }
}
