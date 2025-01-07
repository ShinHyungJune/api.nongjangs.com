<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends ApiController
{
    public function index(Request $request)
    {
        $items = new City();

        $items = $items
            ->orderBy('order', 'asc')
            ->paginate(100);

        return CityResource::collection($items);
    }
}
