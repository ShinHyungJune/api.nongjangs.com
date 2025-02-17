<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CouponGroupUser extends Pivot
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = "coupon_group_user";

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::created(function ($model) {
            Coupon::create([
                'user_id' => $model->user_id,
                'coupon_group_id' => $model->coupon_group_id,
            ]);
        });
    }

}
