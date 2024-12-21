<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatePresetProduct;
use App\Enums\StatePrototype;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\FeedbackRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use App\Models\PresetProduct;
use Illuminate\Http\Request;

class FeedbackController extends ApiController
{
    /** 목록
     * @group Feedback(피드백)
     * @responseFile storage/responses/feedbacks.json
     */
    public function index(FeedbackRequest $request)
    {
        $presetProduct = PresetProduct::where('uuid', $request->preset_product_uuid)->first();

        if(!$presetProduct)
            return $this->respondForbidden('유효하지 않은 주문번호입니다.');

        $items = $presetProduct->feedbacks()->latest()->paginate(60);

        return FeedbackResource::collection($items);
    }

    /** 생성
     * @group Feedback(피드백)
     * @responseFile storage/responses/feedback.json
     */
    public function store(FeedbackRequest $request)
    {
        $presetProduct = PresetProduct::where('uuid', $request->preset_product_uuid)->first();

        if(!$presetProduct)
            return $this->respondForbidden('유효하지 않은 주문번호입니다.');

        if($presetProduct->state_prototype == StatePrototype::CONFIRM)
            return $this->respondForbidden('시안확정 이후로는 피드백을 남길 수 없습니다. 추가요청사항이 있다면 고객센터에 문의해주세요.');

        $feedback = Feedback::create([
            'preset_product_id' => $presetProduct->id,
            'description' => $request->description,
            'admin' => 0
        ]);

        return $this->respondSuccessfully(FeedbackResource::make($feedback));
    }
}
