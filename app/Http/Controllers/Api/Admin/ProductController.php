<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends ApiController
{
    /** 목록
     * @group 관리자
     * @subgroup Product(상품)
     * @priority 12
     * @responseFile storage/responses/products.json
     */
    public function index(ProductRequest $request)
    {
        $items = Product::where('product_id', null)->where('active', 1)->where(function ($query) use ($request) {
            $query->where("title", "LIKE", "%" . $request->word . "%");
        })->orderBy('order', 'asc');

        if (isset($request->open))
            $items = $items->where('open', $request->open);

        if (isset($request->category_id))
            $items = $items->whereHas('categories', function ($query) use($request){
                $query->where('categories.id', $request->category_id);
            });

        $items = $items->latest()->paginate($request->take ?? 10);

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
     * @priority 12
     * @responseFile storage/responses/product.json
     */
    public function store(ProductRequest $request)
    {
        $request['price'] = $request->price_origin - $request->price_discount;
        $createdItem = Product::create($request->all());

        $createdItem->categories()->sync($request->category_ids);

        if (is_array($request->sizes)) {
            foreach ($request->sizes as $size) {
                $createdItem->sizes()->create($size);
            }
        }

        if (is_array($request->colors)) {
            foreach ($request->colors as $color) {
                $createdItem->colors()->create($color);
            }
        }

        if (is_array($request->products)) {
            foreach ($request->products as $product) {
                $createdItem->products()->create($product);
            }
        }


        if (is_array($request->file("imgs"))) {
            foreach ($request->file("imgs") as $file) {
                $createdItem->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        if (is_array($request->file("imgs_prototype"))) {
            foreach ($request->file("imgs_prototype") as $file) {
                $createdItem->addMedia($file["file"])->toMediaCollection("imgs_prototype", "s3");
            }
        }

        if (is_array($request->file("imgs_real"))) {
            foreach ($request->file("imgs_real") as $file) {
                $createdItem->addMedia($file["file"])->toMediaCollection("imgs_real", "s3");
            }
        }

        if (is_array($request->file("imgs_circle"))) {
            foreach ($request->file("imgs_circle") as $file) {
                $createdItem->addMedia($file["file"])->toMediaCollection("imgs_circle", "s3");
            }
        }

        return $this->respondSuccessfully(ProductResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Product(상품)
     * @priority 12
     * @responseFile storage/responses/product.json
     */
    public function update(ProductRequest $request, Product $product)
    {
        $request['price'] = $request->price_origin - $request->price_discount;
        $product->update($request->all());
        $product->categories()->sync($request->category_ids);

        $product->sizes()->update(['open' => 0]);
        $product->colors()->update(['open' => 0]);
        $product->products()->update(['open' => 0]);

        if (is_array($request->sizes)) {
            foreach ($request->sizes as $size) {
                if (!isset($size['id']))
                    $product->sizes()->create($size);

                if (isset($size['id']))
                    Size::find($size['id'])->update(array_merge($size, [
                        'open' => 1
                    ]));
            }
        }

        if (is_array($request->colors)) {
            foreach ($request->colors as $color) {
                if (!isset($color['id']))
                    $product->colors()->create($color);

                if (isset($color['id']))
                    Color::find($color['id'])->update(array_merge($color, [
                        'open' => 1
                    ]));;
            }
        }

        if (is_array($request->products)) {
            foreach ($request->products as $additionalProduct) {
                if (!isset($additionalProduct['id']))
                    $product->products()->create($additionalProduct);

                if (isset($additionalProduct['id']))
                    Product::find($additionalProduct['id'])->update(array_merge($additionalProduct, [
                        'open' => 1
                    ]));
            }
        }

        if ($request->imgs_remove_ids) {
            $medias = $product->getMedia("imgs");

            foreach ($medias as $media) {
                foreach ($request->imgs_remove_ids as $id) {
                    if ((int)$media->id == (int)$id) {
                        $media->delete();
                    }
                }
            }
        }

        if ($request->imgs_prototype_remove_ids) {
            $medias = $product->getMedia("imgs_prototype");

            foreach ($medias as $media) {
                foreach ($request->imgs_prototype_remove_ids as $id) {
                    if ((int)$media->id == (int)$id) {
                        $media->delete();
                    }
                }
            }
        }

        if ($request->imgs_real_remove_ids) {
            $medias = $product->getMedia("imgs_real");

            foreach ($medias as $media) {
                foreach ($request->imgs_real_remove_ids as $id) {
                    if ((int)$media->id == (int)$id) {
                        $media->delete();
                    }
                }
            }
        }

        if ($request->imgs_circle_remove_ids) {
            $medias = $product->getMedia("imgs_circle");

            foreach ($medias as $media) {
                foreach ($request->imgs_circle_remove_ids as $id) {
                    if ((int)$media->id == (int)$id) {
                        $media->delete();
                    }
                }
            }
        }

        if (is_array($request->file("imgs"))) {
            foreach ($request->file("imgs") as $file) {
                $product->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        if (is_array($request->file("imgs_prototype"))) {
            foreach ($request->file("imgs_prototype") as $file) {
                $product->addMedia($file["file"])->toMediaCollection("imgs_prototype", "s3");
            }
        }

        if (is_array($request->file("imgs_real"))) {
            foreach ($request->file("imgs_real") as $file) {
                $product->addMedia($file["file"])->toMediaCollection("imgs_real", "s3");
            }
        }

        if (is_array($request->file("imgs_circle"))) {
            foreach ($request->file("imgs_circle") as $file) {
                $product->addMedia($file["file"])->toMediaCollection("imgs_circle", "s3");
            }
        }

        return $this->respondSuccessfully(ProductResource::make($product));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Product(상품)
     * @priority 12
     */
    public function destroy(ProductRequest $request)
    {
        Product::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }

    public function updateActive(ProductRequest $request)
    {
        Product::whereIn('id', $request->ids)->update(['active' => $request->active]);

        return $this->respondSuccessfully();
    }

    public function up(Product $product, Request $request)
    {
        $prevOrder = $product->order;

        $target = Product::where('product_id', null)->whereHas('categories', function ($query) use($request){
            $query->where('categories.id', $request->category_id);
        })->orderBy('order', 'desc')->where('id', '!=', $product->id)->where('order', '<=', $product->order)->first();

        if($target) {
            $changeOrder = $target->order == $product->order ? $product->order - 1 : $target->order;
            $product->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }

    public function down(Product $product, Request $request)
    {
        $prevOrder = $product->order;

        $target = Product::where('product_id', null)->whereHas('categories', function ($query) use($request){
            $query->where('categories.id', $request->category_id);
        })->orderBy("order", "asc")->where("id", "!=", $product->id)->where("order", ">=", $product->order)->first();

        if($target) {
            $changeOrder = $target->order == $product->order ? $product->order + 1 : $target->order;
            $product->update(["order" => $changeOrder]);
            $target->update(["order" => $prevOrder]);
        }

        return $this->respondSuccessfully();
    }
}
