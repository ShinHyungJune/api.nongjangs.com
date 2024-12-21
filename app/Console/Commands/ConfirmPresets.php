<?php

namespace App\Console\Commands;

use App\Enums\StatePresetProduct;
use App\Enums\TypePointHistory;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ConfirmPresets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'confirm:presets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '자동구매확정';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 등록마감일
        $items = Preset::where("state", StatePresetProduct::DELIVERED)
            ->where("delivery_at", "<=", Carbon::now()->subDays(4)->startOfDay())
            ->cursor();

        foreach($items as $item){
            $item->update(['state' => StatePresetProduct::CONFIRMED]);
        }
    }
}
