<?php

namespace App\Http\Controllers;

use App\Http\Requests\FarmStoryRequest;
use App\Http\Resources\FarmStoryResource;
use App\Models\FarmStory;

class FarmStoryController extends Controller
{
    public function index()
    {
        return FarmStoryResource::collection(FarmStory::all());
    }

    public function store(FarmStoryRequest $request)
    {
        return new FarmStoryResource(FarmStory::create($request->validated()));
    }

    public function show(FarmStory $farmStory)
    {
        return new FarmStoryResource($farmStory);
    }

    public function update(FarmStoryRequest $request, FarmStory $farmStory)
    {
        $farmStory->update($request->validated());

        return new FarmStoryResource($farmStory);
    }

    public function destroy(FarmStory $farmStory)
    {
        $farmStory->delete();

        return response()->json();
    }
}
