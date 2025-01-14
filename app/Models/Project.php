<?php

namespace App\Models;

use App\Enums\StateProject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getStateAttribute()
    {
        if($this->started_at > Carbon::now())
            return StateProject::BEFORE;

        if($this->started_at <= Carbon::now() && $this->finished_at >= Carbon::now())
            return StateProject::ONGOING;

        return StateProject::FINISH;
    }

    public function getRatioProgressAttribute()
    {
        if($this->count_goal == 0)
            return 0;

        return floor($this->count_participate / $this->count_goal * 100);
    }

    public function getDaysRemainAttribute()
    {
        $finishedAt = Carbon::parse($this->finished_at);

        return Carbon::now()->startOfDay()->diffInDays($finishedAt, false); // false: 음수 허용
    }

    public function getTimeRemainAttribute()
    {
        $finishedAt = Carbon::parse($this->finished_at);

        return Carbon::now()->diffInSeconds($finishedAt, false); // false: 음수 허용
    }
}
