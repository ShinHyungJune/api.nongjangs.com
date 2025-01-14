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

}
