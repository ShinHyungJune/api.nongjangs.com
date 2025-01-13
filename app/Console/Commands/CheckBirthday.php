<?php

namespace App\Console\Commands;

use App\Enums\MomentCouponGroup;
use App\Enums\StatePresetProduct;
use App\Enums\TypePointHistory;
use App\Jobs\CheckBirthdayJob;
use App\Models\CouponGroup;
use App\Models\PointHistory;
use App\Models\Preset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CheckBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '생일쿠폰발급';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $couponGroup = CouponGroup::where('moment', MomentCouponGroup::BIRTHDAY)->first();

        if($couponGroup){
            User::whereMonth('birth', Carbon::today()->month)
                ->whereDay('birth', Carbon::today()->day)
                ->whereDoesntHave('coupons', function ($query) use($couponGroup){
                    $query->where('coupon_group_id', $couponGroup->id)
                        ->where('coupons.created_at', '>', Carbon::now()->subYear()->endOfDay());
                })
                ->chunk(1000, function ($users){
                    foreach ($users as $user) {
                        dispatch(new CheckBirthdayJob($user));
                    }
                });
        }

    }
}
