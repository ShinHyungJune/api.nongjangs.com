<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Iamport;
use Illuminate\Support\Facades\Http;

class CardController extends ApiController
{
    /** 목록
     * @group 사용자
     * @subgroup Card(카드)
     * @responseFile storage/responses/cards.json
     */
    public function index()
    {
        $items = auth()->user()->cards()->latest()->paginate(30);

        return CardResource::collection($items);
    }

    public function store(CardRequest $request)
    {
        $result = Iamport::getOrder($request->payment_id);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        $card = auth()->user()->cards()->create([
            'billing_key' => $result['data']['billingKey'],
            'name' => $request->name,
        ]);

        return $this->respondSuccessfully(CardResource::make($card));

        /* $prevCard = auth()->user()->cards()
            ->where('birth_or_business_number', $request->birth_or_business_number)
            ->where('number', $request->number)
            ->first();

        if($prevCard)
            return $this->respondForbidden('이미 등록되어있는 카드입니다.');

        $iamport = new Iamport();

        $result = $iamport->createBillingKey($request->all(), auth()->user());

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        $billingKey = $result['data'];

        $data = $request->validated();

        $data['billing_key'] = $billingKey;

        $card = auth()->user()->cards()->create($data);

        return $this->respondSuccessfully(CardResource::make($card)); */
    }


    public function update(CardRequest $request, Card $card)
    {
        $card->update($request->validated());

        return new CardResource($card);
    }

}
