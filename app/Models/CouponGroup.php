<?php

namespace App\Models;

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
}
