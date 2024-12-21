<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlarmRequest;
use App\Http\Resources\AlarmResource;
use App\Models\Alarm;

class AlarmController extends Controller
{
    public function index()
    {
        return AlarmResource::collection(Alarm::all());
    }

    public function store(AlarmRequest $request)
    {
        return new AlarmResource(Alarm::create($request->validated()));
    }

    public function show(Alarm $alarm)
    {
        return new AlarmResource($alarm);
    }

    public function update(AlarmRequest $request, Alarm $alarm)
    {
        $alarm->update($request->validated());

        return new AlarmResource($alarm);
    }

    public function destroy(Alarm $alarm)
    {
        $alarm->delete();

        return response()->json();
    }
}
