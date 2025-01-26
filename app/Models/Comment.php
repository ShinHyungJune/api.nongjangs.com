<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function getCountLikeAttribute()
    {
        return $this->likes()->count();
    }

    public function getIsLikeAttribute()
    {
        if(!auth()->user())
            return 0;

        return $this->likes()->where('user_id', auth()->id())->exists() ? 1 : 0;
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
