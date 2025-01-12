<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        return ReportResource::collection(Report::all());
    }

    public function store(ReportRequest $request)
    {
        return new ReportResource(Report::create($request->validated()));
    }

    public function show(Report $report)
    {
        return new ReportResource($report);
    }

    public function update(ReportRequest $request, Report $report)
    {
        $report->update($request->validated());

        return new ReportResource($report);
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json();
    }
}
