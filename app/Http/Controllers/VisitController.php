<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitRequest;
use App\Models\Visit;

class VisitController extends Controller
{
    public function index()
    {
        return Visit::all();
    }

    public function store(VisitRequest $request)
    {
        return Visit::create($request->validated());
    }

    public function show(Visit $visit)
    {
        return $visit;
    }

    public function update(VisitRequest $request, Visit $visit)
    {
        $visit->update($request->validated());

        return $visit;
    }

    public function destroy(Visit $visit)
    {
        $visit->delete();

        return response()->json();
    }
}
