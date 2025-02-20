<?php

namespace App\Http\Controllers\Api\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use App\Models\Download;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup User(사용자)
     * @responseFile storage/responses/users.json
     */
    public function index(UserRequest $request)
    {
        $items = User::where(function($query) use($request){
            $query->where("name", "LIKE", "%".$request->word."%")
                ->orWhere("contact", "LIKE", "%".$request->word."%")
                ->orWhere("email", "LIKE", "%".$request->word."%");
        });

        if(isset($request->agree_promotion))
            $items = $items->where('agree_promotion', $request->agree_promotion);

        if(isset($request->subscribe))
            $items = $items->whereHas('packageSetting', function ($query) use($request){
                $query->where('active', $request->subscribe);
            });

        if($request->code_recommend)
            $items = $items->where('code_recommend', $request->code_recommend);

        $items = $items->latest()->paginate(25);

        return UserResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup User(사용자)
     * @responseFile storage/responses/user.json
     */
    public function show(User $user)
    {
        return $this->respondSuccessfully(UserResource::make($user));
    }

    /** 삭제
     * @group 관리자
     * @subgroup User(사용자)
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->respondSuccessfully();
    }


    /** 수정
     * @group 관리자
     * @subgroup User(사용자)
     * @responseFile storage/responses/user.json
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return $this->respondSuccessfully(UserResource::make($user));
    }

    /** 엑셀다운
     * @group 관리자
     * @subgroup User(사용자)
     */
    public function export(UserRequest $request)
    {
        $download = Download::create();

        $path = $download->id."/"."사용자.xlsx";

        $items = User::latest();

        if($request->ids)
            $items = $items->whereIn('id', $request->ids);

        $items = $items->get();

        Excel::store(new UsersExport($items), $path, "s3");

        $url  = Storage::disk("s3")->url($path);

        return $this->respondSuccessfully($url);
    }
}
