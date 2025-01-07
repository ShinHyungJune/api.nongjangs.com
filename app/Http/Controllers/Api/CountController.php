<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountRequest;
use App\Http\Resources\CountResource;
use App\Models\Count;

class CountController extends ApiController
{
    public function index()
    {
        return CountResource::collection(Count::all());
    }

    public function store(CountRequest $request)
    {
        return new CountResource(Count::create($request->validated()));
    }

    public function show(Count $count)
    {
        return new CountResource($count);
    }

    public function update(CountRequest $request, Count $count)
    {
        $count->update($request->validated());

        return new CountResource($count);
    }

    public function destroy(Count $count)
    {
        $count->delete();

        return response()->json();
    }
}
