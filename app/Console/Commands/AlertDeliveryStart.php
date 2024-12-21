<?php

namespace App\Console\Commands;

use App\Enums\StatePresetProduct;
use App\Enums\TypeAlarm;
use App\Enums\TypeDelivery;
use App\Enums\TypePointHistory;
use App\Models\Alarm;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AlertDeliveryStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:deliveryStart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '배송시작알림';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $presetProducts = PresetProduct::where('type_delivery', TypeDelivery::DELIVERY)
            ->where('need_alert_delivery', 1)
            ->distinct('delivery_number')
            ->get();

        foreach($presetProducts as $presetProduct){
            Alarm::create([
                'type' => TypeAlarm::PRESET_PRODUCT_START_DELIVERY,
                'contact' => $presetProduct->preset->order->buyer_contact,
                'preset_product_id' => $presetProduct->id,
            ]);
        }


    }
}
