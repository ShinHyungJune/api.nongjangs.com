<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PhpParser\Node\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Recipe extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    public function getImgAttribute()
    {
        if($this->hasMedia('imgs')) {
            $media = $this->getMedia('imgs')[0];

            return [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return null;
    }

    public function getImgsAttribute()
    {
        $medias = $this->getMedia("imgs");

        $items = [];

        foreach($medias as $media){
            $items[] = [
                "id" => $media->id,
                "name" => $media->file_name,
                "url" => $media->getFullUrl()
            ];
        }

        return $items;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class,'likeable');
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    public function formatMaterials(): Attribute
    {

    }

    public function formatRecipes(): Attribute
    {

    }

    public function formatSeasonings(): Attribute
    {

    }

    public function isBookmark(): Attribute
    {

    }

    public function isLike(): Attribute
    {

    }
}