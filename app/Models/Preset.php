<?php

namespace App\Models;

use App\Enums\StateOrder;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use App\Enums\TypeOption;
use App\Enums\TypePackage;
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
        $total = 0;

        $presetProducts = $this->presetProducts()->cursor();

        foreach($presetProducts as $presetProduct){
            if($presetProduct->product_id){
                if($presetProduct->option_type == TypeOption::REQUIRED)
                    $total += $presetProduct->product_price + $presetProduct->option_price;

                if($presetProduct->option_type == TypeOption::ADDITIONAL)
                    $total += $presetProduct->option_price;
            }

            if($presetProduct->package_id){
                $materials = $presetProduct->materials;

                foreach($materials as $material){
                    $total += $material->pivot->price;
                }
            }
        }

        return $total;
    }

    public function getPriceOriginAttribute()
    {
        $total = 0;

        $presetProducts = $this->presetProducts()->cursor();

        foreach($presetProducts as $presetProduct){
            if($presetProduct->product_id){
                if($presetProduct->option_type == TypeOption::REQUIRED)
                    $total += $presetProduct->product_price_origin + $presetProduct->option_price;

                if($presetProduct->option_type == TypeOption::ADDITIONAL)
                    $total += $presetProduct->option_price;
            }

            if($presetProduct->package_id){
                $materials = $presetProduct->materials;

                foreach($materials as $material){
                    $total += $material->pivot->price_origin;
                }
            }
        }

        return $total;
    }

    public function getPriceDiscountAttribute()
    {
        return $this->price_origin_products - $this->price_products;
    }

    public function getPriceCouponAttribute()
    {
        $presetProducts = $this->presetProducts;

        $total = 0;

        foreach($presetProducts as $presetProduct){
            $total += $presetProduct->price_coupon;
        }

        return $total;
    }

    // 배송비 포함
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

    public function attachProducts($data, $packageSetting = null)
    {
        \DB::beginTransaction();

        try {
            $this->presetProducts()->delete();

            if(isset($data['options'])){
                foreach ($data['options'] as $optionData) {
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
            }

            if(isset($data['package_id'])){
                $package = Package::find($data['package_id']);

                $canOrderPackage = Package::getCanOrder();

                if(!$packageSetting){
                    \DB::rollBack();

                    return [
                        'success' => false,
                        'message' => "구독설정을 먼저 진행해주세요.",
                    ];
                }

                if(!$canOrderPackage){

                    \DB::rollBack();

                    return [
                        'success' => false,
                        'message' => "현재 주문 가능한 회차가 없습니다. 관리자에게 문의하세요.",
                    ];
                }

                if($canOrderPackage->id != $package->id){
                    \DB::rollBack();

                    return [
                        'success' => false,
                        'message' => "해당 회차는 주문 가능한 회차가 아닙니다",
                    ];
                }

                $this->presetProducts()->create([
                    'package_id' => $package->id,
                    'package_name' => $packageSetting->name,
                    'package_count' => $package->count,
                    'package_will_delivery_at' => $package->will_delivery_at,
                    'package_active' => $packageSetting->active,
                    'package_type' => $packageSetting->type,
                    'package_price' => $packageSetting->type == TypePackage::BUNGLE ? $package->price_bungle : $package->price_single,
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
