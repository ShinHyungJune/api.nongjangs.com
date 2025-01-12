<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getCountReviewAttribute()
    {
        return Review::count();
    }

    public function getCountReviewPackageAttribute()
    {
        return Review::whereNotNull('package_id')->count();
    }

    public function getAverageScoreReviewPackageAttribute()
    {
        return Review::whereNotNull('package_id')->average('score');
    }

    public function getCountVegetableStoryAttribute()
    {
        return 0;
    }
}
