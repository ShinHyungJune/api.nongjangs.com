<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StatePresetProduct;
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
    /** 통계
     * @group 관리자
     * @subgroup User(사용자)
     * @responseFile storage/responses/usersCounts.json
     */
    public function counts(User $user)
    {
        $currentPackagePresetProduct = $user->getCurrentPackagePresetProduct();

        $counts = [
            'count_ongoing_order' => $user->count_ongoing_preset_product,
            'total_order_price' => $user->total_order_price,
            'current_package_count' => $currentPackagePresetProduct ? $currentPackagePresetProduct->package_count : 0,
            'count_product' => $user->count_product,

            'count_request_cancel' => $user->presetProducts()->where('state', StatePresetProduct::REQUEST_CANCEL)->count(),
            'sum_cancel_price' => $user->presetProducts()->where('state', StatePresetProduct::CANCEL)->sum('price'),
            'count_cancel' => $user->presetProducts()->where('state', StatePresetProduct::CANCEL)->count(),
            'count_stop_history' => $user->stopHistories()->count(),

            'count_review' => $user->reviews()->count(),
            'count_review_best' => $user->reviews()->where('best', 1)->count(),
            'count_review_package' => $user->reviews()->whereNotNull('package_id')->count(),
            'count_review_package_reply' => $user->reviews()->whereNotNull('package_id')->whereNotNull('reply')->count(),
            'count_review_product' => $user->reviews()->whereNotNull('product_id')->count(),
            'count_review_product_reply' => $user->reviews()->whereNotNull('product_id')->whereNotNull('reply')->count(),
            'count_vegetable_story' => $user->vegetableStories()->count(),
            'count_can_vegetable_story' => $user->presetProducts()->where('state', StatePresetProduct::CONFIRMED)->count() * 3,
        ];

        return $this->respondSuccessfully($counts);
    }

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

        if(isset($request->admin))
            $items = $items->where('admin', $request->admin);

        if(isset($request->agree_promotion))
            $items = $items->where('agree_promotion', $request->agree_promotion);

        if(isset($request->subscribe))
            $items = $items->whereHas('packageSetting', function ($query) use($request){
                $query->where('active', $request->subscribe);
            });

        if($request->code_recommend)
            $items = $items->where('code_recommend', $request->code_recommend);

        if($request->has_column){}
            $items = $items->withTrashed()->whereNotNull($request->has_column);

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

    /** 생성
     * @group 관리자
     * @subgroup User(사용자)
     * @responseFile storage/responses/user.json
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return $this->respondSuccessfully(UserResource::make($user));
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

        if($request->has_column)
            $items = $items->whereNotNull($request->has_column);

        $items = $items->get();

        Excel::store(new UsersExport($items), $path, "s3");

        $url  = Storage::disk("s3")->url($path);

        return $this->respondSuccessfully($url);
    }
}
