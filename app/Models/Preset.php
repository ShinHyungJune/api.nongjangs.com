<?php

namespace App\Models;

use App\Enums\StateOrder;
use App\Enums\TypeDelivery;
use App\Enums\TypeDeliveryPrice;
use App\Enums\TypeDiscount;
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
        $result = $this->checkCanOrder();

        return $result['success'];
    }

    public function checkCanOrder($data = [])
    {
        if(!auth()->user())
            return [
                'success' => 0,
                'message' => '로그인 후 결제시도 가능합니다'
            ];


        if(auth()->id() != $this->user_id)
            return [
                'success' => 0,
                'message' => '자신의 주문건만 결제시도 가능합니다.'
            ];

        if($this->order && $this->order->state != StateOrder::BEFORE_PAYMENT)
            return [
                'success' => 0,
                'message' => '주문준비 상태의 주문건만 결제시도할 수 있습니다.'
            ];

        if(isset($data['delivery_address_zipcode'])) {
            $result = $this->checkCanDeliveryFarPlace($data['delivery_address_zipcode']);

            if (!$result['success'])
                return $result;
        }

        return [
            'success' => 1,
        ];
    }

    // 제주도서산간 배송 가능여부
    public function checkCanDeliveryFarPlace($zipcode)
    {
        $cantDeliveryProduct = null;

        $presetProducts = $this->presetProducts;

        foreach($presetProducts as $presetProduct){
            $product = $presetProduct->product;

            if($product->can_delivery_far_place && $product->ranges_far_place){
                $rangesFarPlace = json_decode($product->ranges_far_place, true);

                foreach($rangesFarPlace as $range){
                    $zipcodeStart = (int) $range['zipcode_start'];
                    $zipcodeEnd = (int) $range['zipcode_end'];

                    if($zipcode >= $zipcodeStart && $zipcode <= $zipcodeEnd){
                        $cantDeliveryProduct = $product;

                        break;
                    }
                }
            }
        }

        if($cantDeliveryProduct)
            return [
                'success' => 0,
                'message' => $cantDeliveryProduct->title." 상품은 해당 주소로 배송 불가능합니다. 고객센터에 상품명 및 주소와 함께 문의해주세요.",
            ];

        return [
            'success' => 1,
        ];
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

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function presetProducts()
    {
        return $this->hasMany(PresetProduct::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot([
            'option_id',
            'count',
        ]);
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
            $total += $presetProduct->price;
            /*if($presetProduct->product_id){
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
            }*/
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

    public function calculatePriceCoupon(Coupon $coupon)
    {
        $couponGroup = $coupon->couponGroup;

        $priceDiscount = 0;

        if($couponGroup->type_discount == TypeDiscount::NUMBER)
            $priceDiscount = $couponGroup->value;

        if($couponGroup->type_discount == TypeDiscount::RATIO)
            $priceDiscount = floor($this->price / 100 * $coupon->value);

        if($couponGroup->max_price_discount < $priceDiscount)
            $priceDiscount = $couponGroup->max_price_discount;

        return $priceDiscount;
    }

    // 배송비 포함
    public function getPriceTotalAttribute()
    {
        return $this->price + $this->price_delivery - $this->price_coupon;
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
                    if($this->price >= $product->min_price_for_free_delivery_price)
                        $priceDelivery = 0;
                    else
                        $priceDelivery = $product->price_delivery;
                }

                if($product->type_delivery_price == TypeDeliveryPrice::PRICE_BY_COUNT){
                    $prices = json_decode($product->prices_delivery);

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

                if($this->order && $this->order->delivery_address_zipcode && $product->ranges_far_place){
                    $rangesFarPlace = json_decode($product->ranges_far_place ?? [], true);

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
                    'package_type' => $packageSetting->type_package,
                    'package_price' => $packageSetting->type_package == TypePackage::BUNGLE ? $package->price_bungle : $package->price_single,
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
