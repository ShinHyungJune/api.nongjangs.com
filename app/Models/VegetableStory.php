<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VegetableStory extends Model implements HasMedia
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

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function presetProduct(): BelongsTo
    {
        return $this->belongsTo(PresetProduct::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getCountLikeAttribute()
    {
        return $this->likes()->count();
    }

    public function getCountBookmarkAttribute()
    {
        return $this->bookmarks()->count();
    }

    public function getCountCommentAttribute()
    {
        return $this->comments()->count();
    }

    public function getIsLikeAttribute()
    {
        if(!auth()->user())
            return 0;

        return $this->likes()->where('user_id', auth()->id())->exists() ? 1 : 0;
    }

    public function getIsBookmarkAttribute()
    {
        if(!auth()->user())
            return 0;

        return $this->bookmarks()->where('user_id', auth()->id())->exists() ? 1 : 0;
    }

    public function getFormatDisplayAttribute()
    {
        return "";
    }
}
