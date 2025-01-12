<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Models\Preset;

class PresetController extends Controller
{
    public function index()
    {
        return PresetResource::collection(Preset::all());
    }

    public function store(PresetRequest $request)
    {
        return new PresetResource(Preset::create($request->validated()));
    }

    public function show(Preset $preset)
    {
        return new PresetResource($preset);
    }

    public function update(PresetRequest $request, Preset $preset)
    {
        $preset->update($request->validated());

        return new PresetResource($preset);
    }

    public function destroy(Preset $preset)
    {
        $preset->delete();

        return response()->json();
    }
}
