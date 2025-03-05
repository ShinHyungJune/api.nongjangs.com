<?php

namespace App\Models;

use App\Enums\StateCouponGroup;
use App\Enums\StateOrder;
use App\Enums\StateProject;
use App\Enums\TargetCouponGroup;
use App\Enums\TypeExpire;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function getHasAttribute()
    {
        // 보유하고 있어도 사용했는지도 체크해야함 (BEFORE_PAYMENT 이상의 preset을 갖고 있으면 쓴거임)
        if(!auth()->user())
            return 0;

        return auth()->user()->coupons()
            ->where('coupon_group_id', $this->id)
            ->where('use', 0)
            ->exists() ? 1 : 0;
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->using(CouponGroupUser::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function canCreateCoupon($user)
    {
        $hasCoupon = $user->coupons()->where('coupon_group_id', $this->id)->first();

        if($hasCoupon)
            return 0;

        if($this->moment)
            return 0;

        // 대상자인지
        if($this->target == TargetCouponGroup::GRADE){
            if($this->grade_id != $user->grade_id)
                return 0;
        }

        if($this->target == TargetCouponGroup::ORDER_HISTORY){
            $latestCountOrder = $user->orders()->where('state', StateOrder::SUCCESS)->where('created_at', '>=', Carbon::now()->subMonths(3)->startOfDay())->count();

            if($this->min_order > $latestCountOrder)
                return 0;
        }

        if($this->target == TargetCouponGroup::PERSONAL){
            if(!$this->users()->where('users.id', $user->id)->exists())
                return 0;
        }

        return 1;
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function getCountUseAttribute()
    {
        return $this->coupons()->where('use', 1)->count();
    }

    public function getCountHasAttribute()
    {
        return $this->coupons()->count();
    }

    public function getStateAttribute()
    {
        if($this->type_expired == TypeExpire::FROM_DOWNLOAD)
            return StateCouponGroup::ONGOING;

        if($this->started_at > Carbon::now())
            return StateCouponGroup::WAIT;

        if($this->started_at <= Carbon::now() && $this->finished_at >= Carbon::now())
            return StateCouponGroup::ONGOING;

        return StateCouponGroup::FINISH;
    }
}
