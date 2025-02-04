<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Grade extends Model implements HasMedia
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

    public function couponGroup()
    {
        return $this->hasOne(CouponGroup::class);
    }

    public function getNeedCountPackageForNextLevelAttribute()
    {
        $nextLevel = Grade::where('level', $this->level + 1)->first();

        if(!$nextLevel)
            return 0;

        if(!auth()->user())
            return 0;

        return $nextLevel->min_count_package - auth()->user()->total_order_count_package;
    }

    public function getNeedPriceForNextLevelAttribute()
    {
        $nextLevel = Grade::where('level', $this->level + 1)->first();

        if(!$nextLevel)
            return 0;

        if(!auth()->user())
            return 0;

        return $nextLevel->min_price - auth()->user()->total_order_price;
    }
}
