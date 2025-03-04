<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlarmRequest;
use App\Http\Resources\AlarmResource;
use App\Models\Alarm;

class ImpController extends ApiController
{
    public function index()
    {
        return $this->respondSuccessfully(config("iamport.imp_code"));
    }

}
