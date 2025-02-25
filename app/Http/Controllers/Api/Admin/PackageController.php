<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Http\Requests\PackageRequest;
use App\Models\Package;
use App\Models\PackageMaterial;
use App\Models\PresetProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Package(꾸러미)
     * @responseFile storage/responses/packages.json
     */
    public function index(PackageRequest $request)
    {
        $items = Package::whereHas('materials', function($query) use($request){
            $query->where("title", "LIKE", "%".$request->word."%");
        });

        $items = $items->latest()->paginate(25);

        return PackageResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Package(꾸러미)
     * @responseFile storage/responses/package.json
     */
    public function show(Package $package)
    {
        return $this->respondSuccessfully(PackageResource::make($package));
    }

    /** 생성
     * @group 관리자
     * @subgroup Package(꾸러미)
     * @responseFile storage/responses/package.json
     */
    public function store(PackageRequest $request)
    {
        $createdItem = Package::create($request->validated());

        foreach($request->packageMaterials as $packageMaterial){
            PackageMaterial::create(array_merge([
                'package_id' => $createdItem->id,
            ], $packageMaterial));
        }

        if($request->recipe_ids)
            $createdItem->recipes()->sync($request->recipe_ids);

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PackageResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Package(꾸러미)
     * @responseFile storage/responses/package.json
     */
    public function update(PackageRequest $request, Package $package)
    {
        if($package->start_pack_at <= Carbon::now())
            return $this->respondForbidden('제품 구성을 변경할 수 있는 기간이 지났습니다.');

        $package->update($request->validated());

        $package->materials()->delete();

        $ids = [];

        foreach($request->packageMaterials as $packageMaterial){
            $ids[] = $packageMaterial['id'];

            if($packageMaterial['id'])
                PackageMaterial::find($packageMaterial['id'])->update($packageMaterial);
            else {
                $createdPackageMaterial = PackageMaterial::create(array_merge([
                    'package_id' => $package->id,
                ], $packageMaterial));

                $ids[] = $createdPackageMaterial->id;
            }
        }

        PackageMaterial::where('package_id', $package->id)->whereNotIn('id', $ids)->delete();

        if($request->recipe_ids)
            $package->recipes()->sync($request->recipe_ids);

        if($request->files_remove_ids){
            $medias = $package->getMedia("img");

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
                $package->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(PackageResource::make($package));
    }

    /** 배송일정 수정
     * @group 관리자
     * @subgroup Package(꾸러미)
     * @responseFile storage/responses/package.json
     */
    public function updateSchedule(PackageRequest $request, Package $package)
    {
        if($package->start_pack_at <= Carbon::now())
            return $this->respondForbidden('이미 품목구성이 시작된 꾸러미입니다.');

        $package->update($request->validated());

        return $this->respondSuccessfully(PackageResource::make($package));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Package(꾸러미)
     */
    public function destroy(PackageRequest $request)
    {
        $packages = Package::whereIn('id', $request->ids)->get();

        foreach($packages as $package){
            if($package->presetProducts()->count() > 0)
                return $this->respondForbidden("[고유번호 : {$package->id}] 해당 꾸러미에 대해 주문시도한 내역이 있어 삭제할 수 없습니다.");
        }

        Package::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
