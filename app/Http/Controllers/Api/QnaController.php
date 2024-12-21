<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QnaRequest;
use App\Http\Resources\QnaResource;
use App\Models\Qna;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class QnaController extends ApiController
{
    /**
     * @group  Qna(문의)
     * @responseFile storage/responses/qnas.json
     */
    public function index(Request $request)
    {
        $items = auth()->user()->qnas()->latest()->paginate(12);

        return QnaResource::collection($items);
    }

    /**
     * @group Qna(문의)
     * @responseFile storage/responses/qna.json
     */
    public function store(QnaRequest $request)
    {
        $data = $request->validated();

        $item = auth()->user()->qnas()->create($data);

        if(is_array($request->file("imgs"))){
            foreach($request->file("imgs") as $file){
                $item->addMedia($file["file"])->toMediaCollection("imgs", "s3");
            }
        }

        return $this->respondSuccessfully(QnaResource::make($item));
    }

}
