<?php

namespace Database\Factories;

use App\Enums\TypePackage;
use App\Models\Card;
use App\Models\Delivery;
use App\Models\Package;
use App\Models\PackageSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PackageSettingFactory extends Factory
{
    protected $model = PackageSetting::class;

    public function definition(): array
    {
        $card = Card::inRandomOrder()->first() ?? Card::factory()->create();
        $delivery = Delivery::inRandomOrder()->first() ?? Delivery::factory()->create();
        $package = Package::inRandomOrder()->first() ?? Package::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'name' => null,
            'type_package' => TypePackage::BUNGLE,
            'term_week' => 3,
            'active' => 1,
            'will_order_at' => Carbon::now()->addWeek(),
            'retry' => 0,

            'user_id' => $user->id,
            'card_id' => $card->id,
            'delivery_id' => $delivery->id,
            'first_package_id' => $package->id,
        ];
    }
}
