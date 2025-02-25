<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\TypeOption;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Option;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Product(상품)
     * @responseFile storage/responses/products.json
     */
    public function index(ProductRequest $request)
    {
        $items = Product::where(function ($query) use ($request) {
            $query->where("title", "LIKE", "%" . $request->word . "%")
                ->orWhere('id', 'LIKE', '%'.$request->word.'%')
                ->orWhereHas('farm', function ($query) use ($request){
                    $query->where('title', 'LIKE', '%'.$request->word.'%');
                });
        })->orderBy('order', 'asc');

        if ($request->category_id)
            $items = $items->where('category_id', $request->category_id);

        $items = $items->latest()->paginate($request->take ?? 25);

        return ProductResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Product(상품)
     * @priority 12
     * @responseFile storage/responses/product.json
     */
    public function show(Product $product)
    {
        return $this->respondSuccessfully(ProductResource::make($product));
    }

    /** 생성
     * @group 관리자
     * @subgroup Product(상품)
     * @responseFile storage/responses/product.json
     */
    public function store(ProductRequest $request)
    {
        if($request->prices_delivery){
            $request['prices_delivery'] = json_encode($request->prices_delivery);
        }

        $createdItem = Product::create($request->validated());

        $createdItem->tags()->sync($request->tag_ids);

        if($request->requiredOptions){
            foreach($request->requiredOptions as $option){
                $createdItem->options()->create(array_merge($option, [
                    'type'=> TypeOption::REQUIRED
                ]));
            }
        }

        if($request->additionalOptions){
            foreach($request->additionalOptions as $option){
                $createdItem->options()->create(array_merge($option, [
                    'type'=> TypeOption::ADDITIONAL
                ]));
            }
        }

        if (is_array($request->file("files"))) {
            foreach ($request->file("files") as $file) {
                $createdItem->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        return $this->respondSuccessfully(ProductResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Product(상품)
     * @responseFile storage/responses/product.json
     */
    public function update(ProductRequest $request, Product $product)
    {
        if($request->prices_delivery)
            $request['prices_delivery'] = json_encode($request->prices_delivery);

        $product->update($request->validated());

        $product->tags()->sync($request->tag_ids);

        $optionIds = [];

        if($request->requiredOptions){
            foreach($request->requiredOptions as $option){
                if($option['id']){
                    $optionIds[] = $option['id'];

                    Option::find($option['id'])->update($option);
                }else{
                    $createdOption = $product->options()->create(array_merge($option, [
                        'type'=> TypeOption::REQUIRED
                    ]));

                    $optionIds[] = $createdOption->id;
                }
            }
        }

        if($request->additionalOptions){
            foreach($request->additionalOptions as $option){
                if($option['id']){
                    $optionIds[] = $option['id'];

                    Option::find($option['id'])->update($option);
                }else{
                    $createdOption = $product->options()->create(array_merge($option, [
                        'type'=> TypeOption::ADDITIONAL
                    ]));

                    $optionIds[] = $createdOption->id;
                }
            }
        }

        $product->options()->whereNotIn("id", $optionIds)->delete();


        if (is_array($request->file("files"))) {
            foreach ($request->file("files") as $file) {
                $product->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        if ($request->files_remove_ids) {
            $medias = $product->getMedia("imgs");

            foreach ($medias as $media) {
                foreach ($request->files_remove_ids as $id) {
                    if ((int)$media->id == (int)$id) {
                        $media->delete();
                    }
                }
            }
        }

        return $this->respondSuccessfully(ProductResource::make($product));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Product(상품)
     */
    public function destroy(ProductRequest $request)
    {
        Product::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }

    /** 상태 수정
     * @group 관리자
     * @subgroup Product(상품)
     */
    public function updateState(Product $product, ProductRequest $request)
    {
        $product->update(['state' => $request->state]);

        return $this->respondSuccessfully();
    }
}
