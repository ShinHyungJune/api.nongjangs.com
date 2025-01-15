<?php

namespace App\Models;

use App\Enums\StateOrder;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use App\Enums\TypeOption;
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
        if(!auth()->user())
            return 0;


        if(auth()->id() != $this->user_id)
            return 0;

        if($this->order && $this->order->state != StateOrder::BEFORE_PAYMENT)
            return 0;

        return 1;
    }

    // 필수옵션개수
    public function getCountOptionRequiredAttribute()
    {
        return $this->presetProducts()->where('option_type', TypeOption::REQUIRED)->sum('count');
    }

    // 추가옵션개수
    public function getCountOptionAdditionalAttribute()
    {
        return $this->presetProducts()->where('option_type', TypeOption::ADDITIONAL)->sum('count');
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

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getPriceDeliveryAttribute()
    {
        return $this->calculatePriceDelivery();
    }

    public function getPriceAttribute()
    {
        return $this->presetProducts()->sum('price');
    }

    public function getPriceDiscountAttribute()
    {
        $presetProducts = $this->presetProducts;

        $total = 0;

        foreach($presetProducts as $presetProduct){
            $option = $presetProduct->option;

            // 필수상품일때만 상품에 대한 할인가가 합산되어야함
            if($option->type == TypeOption::REQUIRED) {
                $price = $presetProduct->product_price_origin - $presetProduct->product_price;

                $total += $price * $presetProduct->count;
            }
        }

        return $total;
    }

    public function getPriceTotalAttribute()
    {
        return $this->price + $this->price_delivery;
    }


    public function calculatePriceDelivery()
    {
        $product = $this->products()->first();

        if($product){
            $priceDelivery = $product->price_delivery;

            if($product->type_delivery == TypeDelivery::FREE)
                $priceDelivery = 0;

            if($product->type_delivery == TypeDelivery::EACH) {
                // 배송비
                if($product->type_delivery_price == TypeDeliveryPrice::STATIC)
                    $priceDelivery = $product->price_delivery;

                if($product->type_delivery_price == TypeDeliveryPrice::CONDITIONAL){
                    if($this->price >= $this->min_price_for_free_delivery_price)
                        $priceDelivery = 0;
                    else
                        $priceDelivery = $product->price_delivery;
                }

                if($product->type_delivery_price == TypeDeliveryPrice::PRICE_BY_COUNT){
                    $prices = json_decode($this->prices_delivery);

                    usort($prices, function ($a, $b) {
                        if ($a['count'] == $b['count'])
                            return 0;

                        return ($a['count'] > $b['count']) ? -1 : 1; // 내림차순 정렬
                    });

                    foreach($prices as $price){
                        if($price['count'] <= $this->count_option_required) {
                            $priceDelivery = $price['price'];

                            break;
                        }
                    }
                }

                $priceDeliveryFarPlace = 0;

                if($this->order && $this->delivery_address_zipcode && $this->ranges_far_place){
                    $rangesFarPlace = json_decode($this->ranges_far_place);

                    foreach($rangesFarPlace as $range){
                        if($range['zipcode_start'] <= $this->order->delivery_address_zipcode && $range['zipcode_end'] >= $this->order->delivery_address_zipcode){
                            $priceDeliveryFarPlace = $range['price'];

                            break;
                        }
                    }
                }

                return $priceDelivery + $priceDeliveryFarPlace;
            }

            return $priceDelivery;
        }

        return 0;
    }

    public function attachProducts($request)
    {

        \DB::beginTransaction();

        try {
            $this->presetProducts()->delete();

            foreach ($request->options as $optionData) {
                // 옵션과 해당 제품 조회
                $option = Option::find($optionData['id']);
                $product = $option->product;

                // 조건을 체크하는 부분 (예시: count가 0이면 실패)
                if ($optionData['count'] > $option->count) {
                    \DB::rollBack();

                    return [
                        'success' => false,
                        'message' => $option->title."의 재고가 부족합니다.",
                    ];
                }

                $this->presetProducts()->create([
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
                'success' => false,
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
                'product_price' => $presetProduct->product->price,
                'product_price_origin' => $presetProduct->product->price_origin,
                'option_title' => $presetProduct->option->title,
                'option_price' => $presetProduct->option->price,
                'option_type' => $presetProduct->option->type,
            ]);
        }
    }
}
