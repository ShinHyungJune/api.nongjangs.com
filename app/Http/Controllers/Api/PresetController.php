<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresetRequest;
use App\Http\Resources\PresetResource;
use App\Models\Option;
use App\Models\Preset;

class PresetController extends ApiController
{
    public function index()
    {
        return PresetResource::collection(Preset::all());
    }

    public function store(PresetRequest $request)
    {
        $preset = auth()->user()->presets()->create();

        $result = $preset->attachProducts($request);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully(PresetResource::make($preset));
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
