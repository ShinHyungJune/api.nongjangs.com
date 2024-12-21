<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $guarded = ["id"];

    use HasFactory;

    protected $casts = [
        'verified_at' => 'timestamp',
    ];
}
