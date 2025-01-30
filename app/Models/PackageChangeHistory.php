<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageChangeHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function presetProduct(): BelongsTo
    {
        return $this->belongsTo(PresetProduct::class);
    }

    public function originPackage(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'origin_package_id');
    }
}
