<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Carbon\Carbon;

class ProjectController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Project(농가살리기 프로젝트)
     * @responseFile storage/responses/projects.json
     */
    public function index()
    {
        $items = new Project();

        $items = $items->where('started_at', '<=', Carbon::now())
            ->where('finished_at', '>=', Carbon::now())
            ->paginate(30);

        return ProjectResource::collection($items);
    }

}
