<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Preset extends Model
{
    protected $guarded = ['id'];

    use HasFactory;

    protected $casts = [
        'delivery_at' => 'date',
    ];

    // 구매가능여부
    public function getCanOrderAttribute()
    {

    }

    // 필수옵션개수
    public function getCountOptionRequiredAttribute()
    {

    }

    // 추가옵션개수
    public function getCountOptionAdditionalAttribute()
    {

    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function presetProducts()
    {
        return $this->hasMany(PresetProduct::class);
    }

    public function attachProducts($request)
    {
        \DB::beginTransaction();

        try {
            foreach ($request->options as $optionData) {
                // 옵션과 해당 제품 조회
                $option = Option::find($optionData['id']);
                $product = $option->product;

                // 조건을 체크하는 부분 (예시: count가 0이면 실패)
                if ($optionData['count'] > $option->count) {
                    \DB::rollBack();

                    return [
                        'success' => 'false',
                        'message' => $option->title."의 재고가 부족합니다.",
                    ];
                }

                // 정상적인 attach 작업
                $this->products()->attach($product->id, [
                    'product_id' => $product->id,
                    'option_id' => $option->id,
                    'product_title' => $product->title,
                    'product_price' => $product->price,
                    'product_price_origin' => $product->price_origin,
                    'count' => $optionData['count'],
                    'option_title' => $option->title,
                    'option_price' => $option->price,
                    'option_type' => $option->type,
                ]);
            }

            // 모든 작업이 정상적으로 완료되면 트랜잭션 커밋
            \DB::commit();

            // 모든 attach가 성공했을 경우 true 반환
            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            // 예외가 발생하면 트랜잭션 롤백
            \DB::rollBack();

            return [
                'success' => 'false',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function syncProducts()
    {
        $presetProducts = $this->presetProducts;

        foreach($presetProducts as $presetProduct){
            $presetProduct->update([
                'product_title' => $presetProduct->product->title,
                'price' => $presetProduct->product->price,
                'price_discount' => $presetProduct->product->price_discount,
                'price_origin' => $presetProduct->product->price_origin,
                'price_delivery' => $presetProduct->product->price_delivery,
                'size_title' => $presetProduct->size ? $presetProduct->size->title : '판매중단 사이즈',
                'size_price' => $presetProduct->size ? $presetProduct->size->price : '-',
                'color_title' => $presetProduct->color ? $presetProduct->color->title : "판매중단 색상",
            ]);
        }
    }
}
