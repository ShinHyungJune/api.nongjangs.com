<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Image extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $appends = [
        "file",
    ];

    public function registerMediaCollections():void
    {
        $this->addMediaCollection('file')->singleFile();
    }

    public function getFileAttribute()
    {
        if($this->hasMedia('file')) {
            $media = $this->getMedia('file')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }
}
