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

    public function getFormatMaterialsAttribute()
    {
        if($this->materials)
            return json_decode($this->materials);

        return [];
    }

    public function getFormatRecipesAttribute()
    {
        if($this->recipes)
            return json_decode($this->recipes);

        return [];
    }

    public function getFormatSeasoningsAttribute()
    {
        if($this->seasonings)
            return json_decode($this->seasonings);

        return [];
    }

    public function getIsBookmarkAttribute()
    {
        if(!auth()->user())
            return 0;

        return $this->bookmarks()->where('user_id', auth()->id())->exists() ? 1 : 0;
    }

    public function getIsLikeAttribute()
    {
        if(!auth()->user())
            return 0;

        return $this->likes()->where('user_id', auth()->id())->exists() ? 1 : 0;
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }
}
