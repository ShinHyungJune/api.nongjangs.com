<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageRequest;
use App\Http\Resources\PackageResource;
use App\Models\Package;

class PackageController extends Controller
{
    public function index()
    {
        return PackageResource::collection(Package::all());
    }

    public function store(PackageRequest $request)
    {
        return new PackageResource(Package::create($request->validated()));
    }

    public function show(Package $package)
    {
        return new PackageResource($package);
    }

    public function update(PackageRequest $request, Package $package)
    {
        $package->update($request->validated());

        return new PackageResource($package);
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return response()->json();
    }
}
