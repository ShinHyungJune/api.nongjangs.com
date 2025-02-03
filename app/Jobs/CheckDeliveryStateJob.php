<?php

namespace App\Jobs;

use App\Enums\CategoryAlarm;
use App\Enums\DeliveryCompany;
use App\Enums\StatePresetProduct;
use App\Models\DeliveryTracker;
use App\Models\Keyword;
use App\Models\Preset;
use App\Models\PresetProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckDeliveryStateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $presetProduct;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PresetProduct $presetProduct)
    {
        $this->presetProduct = $presetProduct;
    }

    public function handle()
    {
        $deliveryTracker = new DeliveryTracker();

        if($this->presetProduct->delivery_number && $this->presetProduct->state == StatePresetProduct::ONGOING_DELIVERY) {
            $result = $deliveryTracker->track(DeliveryCompany::getId($this->presetProduct->delivery_company), $this->presetProduct->delivery_number);

            if($result['success']){
                $delivered = false;

                foreach($result['data'] as $track){
                    if($track['node']['status']['code'] == 'DELIVERED')
                        $delivered = true;
                }

                $this->presetProduct->update([
                    'delivery_tracks' => json_encode($result['data']),
                ]);

                if($delivered)
                    $this->presetProduct->update([
                        'state' => StatePresetProduct::DELIVERED
                    ]);
            }
        }
    }
}
