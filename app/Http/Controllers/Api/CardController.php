<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;

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
        return new CardResource(Card::create($request->validated()));
    }


    public function update(CardRequest $request, Card $card)
    {
        $card->update($request->validated());

        return new CardResource($card);
    }

}
