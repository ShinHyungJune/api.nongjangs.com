<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LogoRequest;
use App\Http\Resources\LogoResource;
use App\Models\Logo;

class LogoController extends ApiController
{
    public function index(LogoRequest $request)
    {
        $items = new Logo();

        if($request->word)
            $items = $items->where('title', 'LIKE', '%'.$request->word.'%');

        $items = $items->paginate(12);

        return LogoResource::collection($items);
    }

    public function show(Logo $logo)
    {
        return $this->respondSuccessfully(LogoResource::make($logo));
    }
}
