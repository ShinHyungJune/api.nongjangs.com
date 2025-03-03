<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Point extends Model
{
    protected $guarded = ['id'];

    use HasFactory;

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::creating(function (Point $point) {
            if(!$point->expired_at)
                $point->expired_at = Carbon::now()->addYear();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }
}
