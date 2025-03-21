<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function presets()
    {
        return $this->hasMany(Preset::class);
    }

    public static function get($user)
    {
        $cart = $user->cart;

        if(!$cart)
            $cart = Cart::create(['user_id' => $user->id]);

        return $cart;
    }
}
