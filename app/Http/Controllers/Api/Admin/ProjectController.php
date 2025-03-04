<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Project(농가프로젝트)
     * @responseFile storage/responses/projects.json
     */
    public function index(ProjectRequest $request)
    {
        $items = new Project();

        if($request->word){
            $items = $items->where(function (Builder $query) use($request) {
                $query->whereHas('product', function ($query) use($request){
                    $query->where('title', 'LIKE', '%'.$request->word.'%')
                        ->orWhere('id', 'LIKE', '%'.$request->word.'%')
                        ->orWhereHas('farm', function ($query) use ($request){
                            $query->where('title', 'LIKE', '%'.$request->word.'%');
                        });
                });
            });
        }

        $items = $items->latest()->paginate(25);

        return ProjectResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Project(농가프로젝트)
     * @responseFile storage/responses/project.json
     */
    public function show(Project $project)
    {
        return $this->respondSuccessfully(ProjectResource::make($project));
    }

    /** 생성
     * @group 관리자
     * @subgroup Project(농가프로젝트)
     * @responseFile storage/responses/project.json
     */
    public function store(ProjectRequest $request)
    {
        $createdItem = Project::create($request->validated());

        $createdItem->tags()->sync($request->tag_ids);

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(ProjectResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Project(농가프로젝트)
     * @responseFile storage/responses/project.json
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        $project->tags()->sync($request->tag_ids);

        if($request->files_remove_ids){
            $medias = $project->getMedia("img");

            foreach($medias as $media){
                foreach($request->files_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $project->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(ProjectResource::make($project));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Project(농가프로젝트)
     */
    public function destroy(ProjectRequest $request)
    {
        Project::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
