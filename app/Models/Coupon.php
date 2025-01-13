<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function couponGroup(): BelongsTo
    {
        return $this->belongsTo(CouponGroup::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function presetProduct()
    {
        return $this->hasOne(PresetProduct::class);
    }

    public function package()
    {
        return $this->hasOne(Package::class);
    }
}
