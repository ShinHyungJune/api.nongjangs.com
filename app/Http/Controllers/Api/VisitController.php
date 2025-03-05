<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\VisitRequest;
use App\Models\Visit;
use Carbon\Carbon;

class VisitController extends ApiController
{
    public function store(VisitRequest $request)
    {
        $visit = Visit::where('ip', $request->ip)->where('created_at', '>=', Carbon::today()->startOfDay())
            ->where('created_at', '<=', Carbon::today()->endOfDay())
            ->first();

        if(!$visit)
            $visit = Visit::create([
                'ip' => $request->ip,
                'user_id' => auth()->user() ? auth()->id() : null
            ]);

        return $this->respondSuccessfully();
    }
}
