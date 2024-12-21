<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayMethodResource;
use App\Models\PayMethod;
use Illuminate\Http\Request;

class PayMethodController extends ApiController
{
    /**
     * @group PayMethod(결제수단)
     * @responseFile storage/responses/payMethods.json
     */
    public function index(Request $request)
    {
        $items = PayMethod::where('used', 1)->latest()->paginate(30);

        return PayMethodResource::collection($items);
    }
}
