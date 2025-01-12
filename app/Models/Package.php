<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $casts = [
        'will_deliveried_at' => 'date',
        'start_pack_wait_at' => 'date',
        'finish_pack_wait_at' => 'date',
        'start_pack_at' => 'date',
        'finish_pack_at' => 'date',
        'start_delivery_ready_at' => 'date',
        'finish_delivery_ready_at' => 'date',
        'start_will_out_at' => 'date',
        'finish_will_out_at' => 'date',
    ];
}
