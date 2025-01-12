<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;

class ProjectController extends ApiController
{
    public function index()
    {
        $items = Project::with(['product']);

        return ProjectResource::collection($items);
    }

    public function store(ProjectRequest $request)
    {
        return new ProjectResource(Project::create($request->validated()));
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json();
    }
}
