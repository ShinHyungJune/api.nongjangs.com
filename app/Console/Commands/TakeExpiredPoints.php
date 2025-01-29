<?php

namespace App\Console\Commands;

use App\Enums\StatePresetProduct;
use App\Enums\TypeAlarm;
use App\Enums\TypeDelivery;
use App\Enums\TypePointHistory;
use App\Models\Alarm;
use App\Models\Point;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\PresetProduct;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TakeExpiredPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'take:expiredPoints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '유효기간 만료포인트 회수';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $points = Point::where('point', '>', 0)
            ->where('expired_at', '<', Carbon::now())
            ->cursor();

        foreach($points as $point){
            $user = User::withTrashed()->find($point->user_id);

            $user->takePoint($point->point, TypePointHistory::EXPIRED, $point, $point);
        }
    }
}
