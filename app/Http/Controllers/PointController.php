<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointRequest;
use App\Http\Resources\PointResource;
use App\Models\Point;

class PointController extends Controller
{
    public function index()
    {
        return PointResource::collection(Point::all());
    }

    public function store(PointRequest $request)
    {
        return new PointResource(Point::create($request->validated()));
    }

    public function show(Point $point)
    {
        return new PointResource($point);
    }

    public function update(PointRequest $request, Point $point)
    {
        $point->update($request->validated());

        return new PointResource($point);
    }

    public function destroy(Point $point)
    {
        $point->delete();

        return response()->json();
    }
}
