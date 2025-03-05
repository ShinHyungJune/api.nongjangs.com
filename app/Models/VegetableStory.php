<?php

namespace App\Models;

use App\Enums\StatePresetProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VegetableStory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    public static $points = [
        80, // 1회
        100, // 2회
        120 // 3호;
    ];

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
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function reports()
    {
        return $this->morphMany(Comment::class, 'commentable');
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

    public function getCountReportsAttribute()
    {
        return $this->reports()->count();
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
        $presetProduct = $this->presetProduct;

        if($presetProduct){
            if($presetProduct->product_id)
                return $presetProduct->product_title;

            if($presetProduct->package_id)
                return "꾸러미 ".$presetProduct->package_count."회차";
        }

        return "";
    }

    public function getCountPresetProductCreateAttribute()
    {
        $user = User::withTrashed()->find($this->user_id);

        return $user->vegetableStories()->where('preset_product_id', $this->preset_product_id)->count();
    }
}
