<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\StopHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StopHistoryFactory extends Factory
{
    protected $model = StopHistory::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'memo' => $this->faker->word(),

            'package_id' => Package::factory(),
        ];
    }
}
