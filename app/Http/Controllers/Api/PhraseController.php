<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhraseRequest;
use App\Http\Resources\PhraseResource;
use App\Models\Phrase;
use Illuminate\Http\Request;

class PhraseController extends ApiController
{
    /**
     * @group Phrase(문구)
     * @responseFile storage/responses/phrases.json
     */
    public function index(PhraseRequest $request)
    {
        $items = new Phrase();

        $request['take'] = $request->take ?? 12;

        if($request->phrase_product_category_id)
            $items = $items->where('phrase_product_category_id', $request->phrase_product_category_id);

        if($request->phrase_receiver_category_id)
            $items = $items->where('phrase_receiver_category_id', $request->phrase_receiver_category_id);

        if($request->word)
            $items = $items->where(function ($query) use ($request){
                $query->where('description', 'LIKE', '%'.$request->word."%")
                    ->orWhereHas('phraseProductCategory', function ($query) use ($request){
                        $query->where('title', 'LIKE', '%'.$request->word.'%');
                    })->orWhereHas('phraseReceiverCategory', function ($query) use ($request){
                        $query->where('title', 'LIKE', '%'.$request->word.'%');
                    });
            });

        $items = $items->orderBy('count_use', 'desc')->paginate($request->take);

        return PhraseResource::collection($items);
    }

    /**
     * @group Phrase(문구)
     */
    public function show(Phrase $phrase)
    {
        $phrase->update(['count_use' => $phrase->count_use + 1]);

        return $this->respondSuccessfully();
    }
}
