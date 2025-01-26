<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeRequest;
use App\Http\Resources\GradeResource;
use App\Models\Grade;

class GradeController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Grade(등급)
     * @responseFile storage/responses/grades.json
     */
    public function index()
    {
        $items = new Grade();

        $items->orderBy('level', 'asc')->paginate(30);

        return GradeResource::collection($items);
    }

}
