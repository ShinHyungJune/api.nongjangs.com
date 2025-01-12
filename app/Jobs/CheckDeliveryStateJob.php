<?php

namespace App\Jobs;

use App\Enums\CategoryAlarm;
use App\Enums\StatePresetProduct;
use App\Models\Keyword;
use App\Models\PresetProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDeliveryStateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $needDeliveryCheckOrderProducts = PresetProduct::where("state", "=", StatePresetProduct::ONGOING_DELIVERY)
            ->cursor();

        foreach($needDeliveryCheckOrderProducts as $needDeliveryCheckOrderProduct){
            $needDeliveryCheckOrderProduct->checkDeliveryState();
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
