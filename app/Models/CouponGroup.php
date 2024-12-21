<?php

namespace App\Models;

use App\Enums\StateEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function isOngoing()
    {
        $events = $this->events;

        foreach($events as $event){
            if($event->state == StateEvent::ONGOING)
                return true;
        }

        return false;
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
