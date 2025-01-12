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
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'count' => $this->faker->randomNumber(),
            'will_deliveried_at' => Carbon::now(),
            'tax' => $this->faker->boolean(),
            'start_pack_wait_at' => Carbon::now(),
            'finish_pack_wait_at' => Carbon::now(),
            'start_pack_at' => Carbon::now(),
            'finish_pack_at' => Carbon::now(),
            'start_delivery_ready_at' => Carbon::now(),
            'finish_delivery_ready_at' => Carbon::now(),
            'start_will_out_at' => Carbon::now(),
            'finish_will_out_at' => Carbon::now(),
        ];
    }
}
