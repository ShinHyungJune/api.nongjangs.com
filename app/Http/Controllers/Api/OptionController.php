<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OptionRequest;
use App\Http\Resources\OptionResource;
use App\Models\Option;

class OptionController extends Controller
{
    public function index()
    {
        return OptionResource::collection(Option::all());
    }

    public function store(OptionRequest $request)
    {
        return new OptionResource(Option::create($request->validated()));
    }

    public function show(Option $option)
    {
        return new OptionResource($option);
    }

    public function update(OptionRequest $request, Option $option)
    {
        $option->update($request->validated());

        return new OptionResource($option);
    }

    public function destroy(Option $option)
    {
        $option->delete();

        return response()->json();
    }
}
