<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function prototype()
    {
        return $this->belongsTo(Prototype::class);
    }

    public function presetProduct()
    {
        return $this->belongsTo(PresetProduct::class);
    }
}
