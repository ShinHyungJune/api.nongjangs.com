<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public $timestamps = false;

    public function getResetUrl()
    {
        return config("app.client_url")."/users/resetPassword?token={$this->token}";
    }
}
