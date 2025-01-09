<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Generator extends Model
{
    use HasFactory;

    public static function createUuid($model, $length = 16, $column = "uuid")
    {
        $uuid = Uuid::uuid4()->toString();

        $uuid = hexdec(substr($uuid, 0, $length));

        $uuid = $uuid % 10000000000;

        $uuid = str_pad($uuid, $length, '0', STR_PAD_LEFT);

        if($model->where($column, $uuid)->first())
            return Generator::createUuid($model);

        return $uuid;
    }


}
