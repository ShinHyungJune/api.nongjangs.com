<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GradeResource;
use App\Http\Requests\GradeRequest;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradeController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Grade(등급)
     * @responseFile storage/responses/grades.json
     */
    public function index(GradeRequest $request)
    {
        $items = Grade::where(function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return GradeResource::collection($items);
    }
}
