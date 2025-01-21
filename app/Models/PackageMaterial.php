<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageMaterial extends Model
{
    use HasFactory;

    protected $table = 'package_material';

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
