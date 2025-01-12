<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportCategoryRequest;
use App\Http\Resources\ReportCategoryResource;
use App\Models\ReportCategory;

class ReportCategoryController extends Controller
{
    public function index()
    {
        return ReportCategoryResource::collection(ReportCategory::all());
    }

    public function store(ReportCategoryRequest $request)
    {
        return new ReportCategoryResource(ReportCategory::create($request->validated()));
    }

    public function show(ReportCategory $reportCategory)
    {
        return new ReportCategoryResource($reportCategory);
    }

    public function update(ReportCategoryRequest $request, ReportCategory $reportCategory)
    {
        $reportCategory->update($request->validated());

        return new ReportCategoryResource($reportCategory);
    }

    public function destroy(ReportCategory $reportCategory)
    {
        $reportCategory->delete();

        return response()->json();
    }
}
