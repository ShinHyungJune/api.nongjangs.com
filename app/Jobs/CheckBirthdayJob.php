<?php

namespace App\Jobs;

use App\Enums\CategoryAlarm;
use App\Enums\MomentCouponGroup;
use App\Enums\StatePresetProduct;
use App\Models\Coupon;
use App\Models\CouponGroup;
use App\Models\Keyword;
use App\Models\PresetProduct;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckBirthdayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $couponGroup = CouponGroup::where('moment', MomentCouponGroup::BIRTHDAY)->first();

        Coupon::create([
            'coupon_group_id' => $couponGroup->id,
            'user_id' => $this->user->id,
        ]);
    }
}
