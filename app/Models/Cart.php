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

    public static function get($user = null, $guestId = null)
    {
        $cart = null;

        if($user)
            return $user->cart;

        if($guestId) {
            $cart = Cart::where('guest_id', $guestId)->first();

            if(!$cart)
                $cart = Cart::create(['guest_id' => $guestId]);
        }

        return $cart;
    }

    public function presets()
    {
        return $this->hasMany(Preset::class);
    }
}
