<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Notice extends Model implements HasMedia
{

    protected $guarded = ["id"];

    use HasFactory, InteractsWithMedia;

    public function getFormatYearAttribute()
    {
        return Carbon::make($this->created_at)->format("Y");
    }

    public function getFormatMonthAttribute()
    {
        return Carbon::make($this->created_at)->format("mm");
    }

    public function getFormatDateAttribute()
    {
        return Carbon::make($this->created_at)->format("dd");
    }
}
