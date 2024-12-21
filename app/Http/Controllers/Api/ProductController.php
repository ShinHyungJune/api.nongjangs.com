<?php

namespace App\Http\Controllers\Api;

use App\Enums\StateTransactionProduct;
use App\Enums\TypeProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Chat;
use App\Models\City;
use App\Models\Keyword;
use App\Models\Location;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends ApiController
{
    /**
     * @group Product(상품)
     * @priority 3
     * @responseFile storage/responses/products.json
     */

    public function index(ProductRequest $request)
    {
        $request['order_by'] = $request->order_by ?? 'created_at';
        $request['align'] = $request->align ?? 'desc';
        $request['take'] = $request->take ?? 12;

        $items = Product::where('open', 1)->where('custom', 0)->where('product_id', null);

        if (isset($request->category_id))
            $items = $items->whereHas('categories', function ($query) use($request){
                $query->where('categories.id', $request->category_id);
            });

        if($request->recommend)
            $items = $items->where('recommend', $request->recommend)->orderBy('order', 'asc');

        if($request->word)
            $items = $items->where('title', 'LIKE', '%'.$request->word.'%');

        if($request->random)
            $items = $items->orderBy('count_order', 'desc')->take(6)->inRandomOrder();

        if(!$request->random)
            $items->orderBy($request->order_by, $request->align);

        if($request->price_min)
            $items = $items->where('price', ">=", $request->price_min);

        if($request->price_max)
            $items = $items->where('price', "<=", $request->price_max);

        $items = $items->orderBy($request->order_by, $request->align)->orderBy('id', 'desc')->paginate($request->take);

        return ProductResource::collection($items);
    }

    /**
     * @group Product(상품)
     * @responseFile storage/responses/product.json
     */
    public function show(Product $product)
    {
        $product->update(["count_view" => $product->count_view + 1]);

        return $this->respondSuccessfully(ProductResource::make($product));
    }

    public function naver(Request $request)
    {
        $products = Product::whereHas('categories')->where('custom', 0)->where('open', 1)->cursor();

        $headers = [
            'id',               // 상품 ID
            'title',            // 상품명
            'price_pc',         // PC 가격
            'link',             // 상품 URL
            'image_link',       // 대표 이미지 URL
            'category_name1',   // 대분류 카테고리명
            'shipping',         // 배송비
            'condition',        // 상품 상태
            'import_flag',      // 해외 구매 대행 여부
            'parallel_import',  // 병행수입 여부
            'adult',            // 미성년자 구매불가 여부
        ];

        // TSV 데이터 생성
        $tsvData = implode("\t", $headers) . "\n"; // 헤더 추가

        foreach ($products as $product) {
            $tsvData .= implode("\t", [
                    $product->id,                    // 상품 ID
                    $product->title,                 // 상품명
                    $product->price,                 // PC 가격
                    config('app.client_url').'/products/'.$product->id,// 상품 URL
                    $product->img ? $product->img['url'] : '',                   // 대표 이미지 URL
                    $product->categories()->first()->title, // 대분류 카테고리명
                    $product->price_delivery ?? '0',       // 배송비 (기본 무료)
                    '신상품',                         // 상품 상태
                    'N',                             // 해외 구매 대행 여부
                    'N',                             // 병행수입 여부
                    'N',                             // 미성년자 구매불가 여부
                ]) . "\n";
        }

        // TSV 파일 반환
        return \Response::make($tsvData, 200, [
            'Content-Type' => 'text/tab-separated-values',
            'Content-Disposition' => 'attachment; filename="naver_ep_feed.tsv"',
        ]);
    }
}
