<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup User(사용자)
     * @priority 7
     * @responseFile storage/responses/users.json
     */
    public function index(UserRequest $request)
    {
        $items = User::where(function($query) use($request){
            $query->where("name", "LIKE", "%".$request->word."%")
                ->orWhere("contact", "LIKE", "%".$request->word."%")
                ->orWhere("email", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(10);

        return UserResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup User(사용자)
     * @priority 7
     * @responseFile storage/responses/user.json
     */
    public function show(User $user)
    {
        return $this->respondSuccessfully(UserResource::make($user));
    }

    /** 생성
     * @group 관리자
     * @subgroup User(사용자)
     * @priority 7
     * @responseFile storage/responses/user.json
     */
    public function store(UserRequest $request)
    {
        $createdItem = User::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(UserResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup User(사용자)
     * @priority 7
     * @responseFile storage/responses/user.json
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        if($request->files_remove_ids){
            $medias = $user->getMedia("img");

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
                $user->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(UserResource::make($user));
    }

    /** 삭제
     * @group 관리자
     * @subgroup User(사용자)
     * @priority 7
     */
    public function destroy(UserRequest $request)
    {
        User::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
