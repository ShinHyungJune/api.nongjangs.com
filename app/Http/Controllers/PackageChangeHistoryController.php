<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageChangeHistoryRequest;
use App\Http\Resources\PackageChangeHistoryResource;
use App\Models\PackageChangeHistory;

class PackageChangeHistoryController extends Controller
{
    public function index()
    {
        return PackageChangeHistoryResource::collection(PackageChangeHistory::all());
    }

    public function store(PackageChangeHistoryRequest $request)
    {
        return new PackageChangeHistoryResource(PackageChangeHistory::create($request->validated()));
    }

    public function show(PackageChangeHistory $packageChangeHistory)
    {
        return new PackageChangeHistoryResource($packageChangeHistory);
    }

    public function update(PackageChangeHistoryRequest $request, PackageChangeHistory $packageChangeHistory)
    {
        $packageChangeHistory->update($request->validated());

        return new PackageChangeHistoryResource($packageChangeHistory);
    }

    public function destroy(PackageChangeHistory $packageChangeHistory)
    {
        $packageChangeHistory->delete();

        return response()->json();
    }
}
