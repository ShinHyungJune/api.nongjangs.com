<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phrase extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function phraseProductCategory()
    {
        return $this->belongsTo(PhraseProductCategory::class);
    }

    public function phraseReceiverCategory()
    {
        return $this->belongsTo(PhraseReceiverCategory::class);
    }
}
