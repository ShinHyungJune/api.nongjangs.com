<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Http\Requests\PackageRequest;
use App\Models\Package;
use App\Models\PackageMaterial;
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

        foreach($request->materials as $material){
            $packageMaterial = PackageMaterial::create(array_merge([
                'package_id' => $createdItem->id,
                'material_id' => $material['id'],
            ], $material));

            $packageMaterial->tags()->sync($material['tag_ids']);
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

        foreach($request->materials as $material){
            $packageMaterial = PackageMaterial::create(array_merge([
                'package_id' => $package->id,
                'material_id' => $material['id'],
            ], $material));

            $packageMaterial->tags()->sync($material['tag_ids']);
        }

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

    /** 삭제
     * @group 관리자
     * @subgroup Package(꾸러미)
     */
    public function destroy(PackageRequest $request)
    {
        Package::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }
}
