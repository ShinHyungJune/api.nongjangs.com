<?php

namespace App\Models;

use App\Enums\TypePointHistory;
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

    public function getFormatTitleAttribute()
    {
        $title = "[{$this->format_increase}] ";

        $pointHistoriable = $this->pointHistoriable;
        $formatType = TypePointHistory::getLabel($this->type);

        if($pointHistoriable && $this->type == TypePointHistory::ORDER_CREATED){
            return $title.$formatType." : ".$pointHistoriable->format_products;
        }

        if($pointHistoriable && $this->type == TypePointHistory::PRESET_PRODUCT_CANCLE){
            return $title.$formatType." : ".$pointHistoriable->format_title;
        }

        if($pointHistoriable && $this->type == TypePointHistory::PHOTO_REVIEW_CREATED){
            return $title.$formatType." : ".$pointHistoriable->format_title;
        }

        if($pointHistoriable && $this->type == TypePointHistory::TEXT_REVIEW_CREATED){
            return $title.$formatType." : ".$pointHistoriable->format_title;
        }

        if($pointHistoriable && $this->type == TypePointHistory::BEST_REVIEW_UPDATED){
            return $title.$formatType." : ".$pointHistoriable->format_title;
        }

        if($pointHistoriable && $this->type == TypePointHistory::PRESET_PRODUCT_CONFIRM){
            return $title.$formatType." : ".$pointHistoriable->format_title;
        }

        return $title.$formatType;
    }

    public function pointHistoriable()
    {
        return $this->morphTo();
    }

    public function pointModel()
    {
        return $this->belongsTo(Point::class);
    }
}
