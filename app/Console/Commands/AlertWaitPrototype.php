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

class AlertWaitPrototype extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:waitPrototype';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '아직 시안 확정 안된 시안 확정요청하기';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $presetProducts = PresetProduct::where(function ($query){
            $query->where('state', StatePresetProduct::FINISH_PROTOTYPE);
        })->where('alert_send_check_prototype_message_at', '<=', Carbon::now()->subHours(48))
            ->cursor();

        foreach($presetProducts as $presetProduct){
            $presetProduct->update(['alert_send_check_prototype_message_at' => Carbon::now()]);

            $prototype = $presetProduct->prototypes()->latest()->first();

            if($prototype){
                Alarm::create([
                    'contact' => $presetProduct->preset->order->buyer_contact,
                    'prototype_id' => $presetProduct->prototypes()->latest()->first()->id,
                    'type' => TypeAlarm::PROTOTYPE_CREATED,
                ]);
            }
        }
    }
}
