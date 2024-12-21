<?php

namespace App\Http\Resources;

use App\Enums\DeliveryCompany;
use App\Enums\StatePresetProduct;
use App\Enums\StatePrototype;
use App\Enums\TypeDelivery;
use App\Enums\TypeProduct;
use App\Models\Category;
use App\Models\Color;
use App\Models\Logo;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class PresetProductMiniResource extends JsonResource
{
    public function toArray($request)
    {
        $product = Product::withTrashed()->find($this->product_id);
        $prototypes = [];

        foreach($this->prototypes as $prototype){
            $prototypes[] = [
                'id' => $prototype->id
            ];
        }

        $logo = Logo::withTrashed()->find($this->logo_id);

        return [
            'id' => $this->id,

            'logo' => $this->logo ? LogoResource::make($logo) : '',

            'present' => $this->present,
            'uuid' => $this->uuid,
            'additional' => $this->resource->additional,
            'count' => $this->count,
            'price_total' => $this->price_total,


            'state' => $this->state,
            'format_state' => $this->state ? StatePresetProduct::getLabel($this->state) : '',

            'preset' => [
                'id' => $this->preset->id,
                'order' => $this->preset->order ? [
                    'id' => $this->preset->order->id,
                    'count_preset' => $this->preset->order->presets()->count(),
                    'buyer_name' => $this->preset->order->buyer_name,
                    'buyer_contact' => $this->preset->order->buyer_contact,
                    'payMethod' => [
                        'id' => $this->preset->order->pay_method_id,
                        'name' => $this->preset->order->pay_method_name,
                    ],
                    'format_created_at' => Carbon::make($this->preset->order->created_at)->format('Y.m.d H:i'),
                ] : '',
            ],

            'product' => [
                'id' => $product->id,
                'img' => $product->img ?? '', // 대표이미지
                'title' => $this->product_title,
                'price' => $this->price,
                'price_discount' => $this->price_discount,
                'price_origin' => $this->price_origin,
                'price_delivery' => $this->price_delivery,
                /*'category' => $product->category ? [
                    'id' => $product->category->id,
                ] : '',*/
            ],

            'size' => [
                'title' => $this->size_title,
                'price' => $this->size_price,
            ],
            'color' => [
                'title' => $this->color_title,
            ],

            'submit_request' => $this->submit_request, // 시안작성여부
            'title' => $this->title,
            'prototypes' => $prototypes,
            'confirm_prototype' => $this->confirm_prototype,
            'will_prototype_finished_at' => $this->will_prototype_finished_at,
            'format_will_prototype_finished_at' => $this->will_prototype_finished_at ? Carbon::make($this->will_prototype_finished_at)->format('Y-m.d') : '',
            'type_delivery' => $this->type_delivery,
            'format_type_delivery' => TypeDelivery::getLabel($this->type_delivery),
            'delivery_number' => $this->delivery_number ?? '',
            'delivery_company' => $this->delivery_company ?? '',
            'format_delivery_company' => $this->delivery_company ? DeliveryCompany::getLabel($this->delivery_company) : '',
            'delivery_at' => $this->delivery_at,
            'format_delivery_at' => $this->delivery_at ? Carbon::make($this->delivery_at)->format('Y-m.d') : '',
            'delivery_url' => $this->delivery_url ?? '',

            'can_review' => $this->can_review, // 리뷰가능여부
            'can_order' => $this->can_order, // 구매가능여부
            'url_reorder' => $this->url_reorder, // 재주문 url

            'can_refund' => $this->can_refund,
            'can_confirm' => $this->can_confirm,
            'need_alert_delivery' => $this->need_alert_delivery,
            'state_prototype' => $this->state_prototype,
            'format_state_prototype' => StatePrototype::getLabel($this->state_prototype),

            'format_updated_at' => Carbon::make($this->updated_at)->format("Y.m.d"),
        ];
    }
}
