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

class AlertReviewNeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:reviewNeed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '리뷰작성유도';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $presetProducts = PresetProduct::where(function ($query){
            $query->where('state', StatePresetProduct::DELIVERED)
                ->orWhere('state', StatePresetProduct::CONFIRMED);
        })->where('need_alert_review', 1)
            ->where('additional', 0)
            ->cursor();

        foreach($presetProducts as $presetProduct){
            if($presetProduct->preset){
                $presetProduct->update([
                    'need_alert_review' => 0
                ]);

                Alarm::create([
                    'type' => TypeAlarm::REVIEW_REQUIRED,
                    'contact' => $presetProduct->preset->order->buyer_contact,
                    'preset_product_id' => $presetProduct->id,
                ]);
            }
        }
    }
}
