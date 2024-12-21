<?php

namespace App\Console\Commands;

use App\Enums\StatePresetProduct;
use App\Enums\TypePointHistory;
use App\Jobs\CheckDeliveryStateJob;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CheckDeliveryState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:deliveryStates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '택배현황조회';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dispatch(new CheckDeliveryStateJob());
    }
}
