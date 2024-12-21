<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\PresetResource;
use App\Http\Resources\SaleProductResource;
use App\Models\Cart;
use App\Models\Preset;
use App\Models\SaleProduct;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    /**
     * @group Cart(장바구니)
     * @responseFile storage/responses/presets.json
     * */
    public function index(CartRequest $request)
    {
        $cart = Cart::get(auth()->user(), $request->guest_id);

        $items = $cart->presets()->latest()->paginate(12);

        $items->map(function ($item){
           $item->syncProducts();

           return $item;
        });

        return $this->respondSuccessfully(PresetResource::collection($items));
    }

    /**
     * @group Cart(장바구니)
     * */
    public function store(CartRequest $request)
    {
        $cart = Cart::get(auth()->user(), $request->guest_id);

        $preset = Preset::find($request->preset_id);

        if(auth()->user() && $preset->user_id != auth()->id())
            return $this->respondForbidden();

        if(!auth()->user() && $preset->guest_id != $request->guest_id)
            return $this->respondForbidden();

        if($cart->presets()->count() >= 100)
            return $this->respondForbidden('최대 100개까지만 담을 수 있습니다.');

        /*
        $prevSaleProduct = auth()->user()->cart->saleProducts()->where('sale_products.id', $request->sale_product_id)->first();

        if($prevSaleProduct){
            auth()->user()->cart->saleProducts()->updateExistingPivot($prevSaleProduct->id, [
                'count' => $prevSaleProduct->pivot->count + $request->count
            ]);

            return $this->respondSuccessfully();
        }*/

        $preset->update([
            'cart_id' => $cart->id,
            'count' => 1,
        ]);

        return $this->respondSuccessfully();
    }

    /**
     * @group Cart(장바구니)
     * */

    public function update(CartRequest $request)
    {
        $cart = Cart::get(auth()->user(), $request->guest_id);

        $preset = $cart->presets()->find($request->preset_id);

        $preset->update(['count' => $request->count]);

        return $this->respondSuccessfully();
    }

    /**
     * @group Cart(장바구니)
     * */

    public function destroy(CartRequest $request)
    {
        $cart = Cart::get(auth()->user(), $request->guest_id);

        $cart->presets()->whereIn('presets.id', $request->preset_ids)->update(['cart_id' => null]);

        return $this->respondSuccessfully();
    }
}
