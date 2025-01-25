<?php

namespace App\Http\Controllers;

use App\Http\Requests\StopHistoryRequest;
use App\Http\Resources\StopHistoryResource;
use App\Models\StopHistory;

class StopHistoryController extends Controller
{
    public function index()
    {
        return StopHistoryResource::collection(StopHistory::all());
    }

    public function store(StopHistoryRequest $request)
    {
        return new StopHistoryResource(StopHistory::create($request->validated()));
    }

    public function show(StopHistory $stopHistory)
    {
        return new StopHistoryResource($stopHistory);
    }

    public function update(StopHistoryRequest $request, StopHistory $stopHistory)
    {
        $stopHistory->update($request->validated());

        return new StopHistoryResource($stopHistory);
    }

    public function destroy(StopHistory $stopHistory)
    {
        $stopHistory->delete();

        return response()->json();
    }
}
