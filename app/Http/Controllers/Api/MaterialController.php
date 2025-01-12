<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialRequest;
use App\Http\Resources\MaterialResource;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        return MaterialResource::collection(Material::all());
    }

    public function store(MaterialRequest $request)
    {
        return new MaterialResource(Material::create($request->validated()));
    }

    public function show(Material $material)
    {
        return new MaterialResource($material);
    }

    public function update(MaterialRequest $request, Material $material)
    {
        $material->update($request->validated());

        return new MaterialResource($material);
    }

    public function destroy(Material $material)
    {
        $material->delete();

        return response()->json();
    }
}
