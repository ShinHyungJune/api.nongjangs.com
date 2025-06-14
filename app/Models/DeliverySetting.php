<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverySetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function deliveryCompany()
    {
        return $this->belongsTo(DeliveryCompany::class);
    }
}
