<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
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
        $prevCard = auth()->user()->cards()
            ->where('card_number', $request->card_number)
            ->where('cvc', $request->cvc)
            ->first();

        if($prevCard)
            return $this->respondForbidden('이미 등록되어있는 카드입니다.');

        $response = Http::withHeaders([
            'Authorization' => 'PortOne ' . config('iamport.secret'),
            'Content-Type' => 'application/json',
        ])->withoutVerifying()
            ->post('https://api.portone.io/billing-keys', [
            'storeId' => config('iamport.store_id'),
            'channelKey' => config('iamport.channel_key'),
            'customer' => [
                'id' => (string) auth()->id(),
                'email' => auth()->user()->email,
                'name' => ['full' => auth()->user()->nickname],
                'phoneNumber' => auth()->user()->contact,
            ],
            'method' => [
                'card' => [
                    'credential' => [
                        'number' => $request->number,
                        'expiryYear' => $request->expiry_year,
                        'expiryMonth' => $request->expiry_month,
                        'birthOrBusinessRegistrationNumber' => $request->birth_or_business_number,
                        'passwordTwoDigits' => $request->password,
                    ],
                ],
            ],
        ]);

        $result = $response->json();

        if(!isset($result['billingKeyInfo']))
            return $this->respondForbidden(isset($result['pgMessage']) ? $result['pgMessage'] : '카드등록에 실패하였습니다. 카드정보를 확인해주세요.');

        $data['billing_key'] = $result['billingKeyInfo']['billingKey'];

        $data = $request->validated();

        $card = auth()->user()->cards()->create($data);

        return $this->respondSuccessfully(CardResource::make($card));
    }


    public function update(CardRequest $request, Card $card)
    {
        $card->update($request->validated());

        return new CardResource($card);
    }

}
