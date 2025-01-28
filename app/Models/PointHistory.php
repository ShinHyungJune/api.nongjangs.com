<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getFormatIncreaseAttribute()
    {
        return $this->increase ? '적립' : '사용';
    }

    public function pointHistoriable()
    {
        return $this->morphTo();
    }
}
