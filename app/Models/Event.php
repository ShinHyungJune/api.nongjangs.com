<?php

namespace App\Models;

use App\Enums\StateEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('img')->singleFile();
    }

    public function getImgAttribute()
    {
        if($this->hasMedia('img')) {
            $media = $this->getMedia('img')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function getStateAttribute()
    {
        if($this->started_at > Carbon::now()->endOfDay())
            return StateEvent::WAIT;

        if($this->finished_at < Carbon::now()->startOfDay())
            return StateEvent::FINISH;

        return StateEvent::ONGOING;
    }

    public function getFormatStateAttribute()
    {
        return StateEvent::getLabel($this->state);
    }

    public function couponGroup()
    {
        return $this->belongsTo(CouponGroup::class);
    }
}
