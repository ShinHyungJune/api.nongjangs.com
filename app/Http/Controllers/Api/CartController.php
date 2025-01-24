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
    /** 목록
     * @group 사용자
     * @subgroup Cart(장바구니)
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

    /** 담기
     * @group 사용자
     * @subgroup Cart(장바구니)
     * */
    public function store(CartRequest $request)
    {
        $cart = Cart::get(auth()->user());

        $preset = Preset::find($request->preset_id);

        if(!$preset->can_order)
            return $this->respondForbidden('장바구니에 담을 수 없습니다.');

        /*if(!auth()->user() && $preset->guest_id != $request->guest_id)
            return $this->respondForbidden();*/

        if($cart->presets()->count() >= 100)
            return $this->respondForbidden('최대 100개까지만 담을 수 있습니다.');

        $preset->update([
            'cart_id' => $cart->id,
        ]);

        return $this->respondSuccessfully();
    }

    /** 수정
     * @group 사용자
     * @subgroup Cart(장바구니)
     * */

    public function update(CartRequest $request)
    {
        $cart = Cart::get(auth()->user(), $request->guest_id);

        $preset = $cart->presets()->find($request->preset_id);

        $preset->update(['count' => $request->count]);

        return $this->respondSuccessfully();
    }

    /** 삭제
     * @group 사용자
     * @subgroup Cart(장바구니)
     * */
    public function destroy(CartRequest $request)
    {
        $cart = Cart::get(auth()->user(), $request->guest_id);

        foreach($request->preset_ids as $id){
            $preset = $cart->presets()->find($id);

            if($preset->can_order)
                $preset->delete();
        }

        return $this->respondSuccessfully();
    }
}
