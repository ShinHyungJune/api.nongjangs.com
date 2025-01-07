<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pop extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'date',
        'finished_at' => 'date',
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
}
