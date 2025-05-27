<?php

namespace App\Http\Controllers\Api;

use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Card;
use App\Models\History;
use App\Models\Iamport;
use App\Models\Order;
use App\Models\PayMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends ApiController
{
    /** 배송비 계산
     * @group 사용자
     * @subgroup Order(주문)
     * @responseFile storage/responses/priceDelivery.json
     */
    public function calculatePriceDelivery(Order $order)
    {
        $total = 0;

        $presets = $order->presets;

        foreach($presets as $preset){
            $total += $preset->price_delivery;
        }

        return $this->respondSuccessfully([
            'price_delivery' => $total
        ]);
    }

    /** 목록
     * @group 사용자
     * @subgroup Order(주문)
     * @responseFile storage/responses/orders.json
     */
    public function index(OrderRequest $request)
    {
        $items = auth()->user()->orders();

        $items = $items->where('state', '!=', StateOrder::BEFORE_PAYMENT);

        if($request->started_at)
            $items = $items->where('created_at', '>=', Carbon::make($request->started_at)->startOfDay());

        if($request->finished_at)
            $items = $items->where('created_at', '<=', Carbon::make($request->finished_at)->endOfDay());

        if($request->word)
            $items = $items->whereHas('presetProducts', function ($query) use($request){
                $query->where('product_title', 'LIKE', '%'.$request->word.'%')
                    ->orWhere('package_name', 'LIKE', '%'.$request->word.'%');
            });

        if($request->has_column)
            $items = $items->whereHas('presetProducts', function ($query) use($request){
                $query->whereNotNull($request->has_column);
            });

        if($request->states)
            $items = $items->whereHas('presetProducts', function ($query) use($request){
                $query->whereIn('preset_product.state', $request->states);
            });

        $items = $items->latest()->paginate(12);

        return OrderResource::collection($items);
    }

    /** 생성
     * @group 사용자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function store(OrderRequest $request)
    {
        if(!auth()->user() && !$request->guest_id)
            return $this->respondForbidden('비회원은 게스트 고유번호값이 필수입니다. 관리자에게 문의하세요.');

        $order = new Order();

        $result = $order->ready($request->presets, auth()->user(), $request->guest_id);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        $request['buyer_contact'] = str_replace("-", "", $request->buyer_contact);

        $createdOrder = $result['data'];

        return $this->respondSuccessfully(OrderResource::make($createdOrder));
    }

    /** 수정(결제시도)
     * @group 사용자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function update(Order $order, OrderRequest $request)
    {
        if(auth()->user() && $order->user_id != auth()->id())
            return $this->respondForbidden();

        if(!auth()->user() && $order->guest_id != $request->guest_id)
            return $this->respondForbidden();

        if($request->is_save_delivery_requirement)
            auth()->user()->update([
                'delivery_requirement' => $request->delivery_requirement
            ]);

        if($order->state != StateOrder::BEFORE_PAYMENT)
            return $this->respondForbidden('결제준비중인 주문건에 대해서만 결제요청을 할 수 있습니다.');

        $result = DB::transaction(function () use($order, $request){
            return $order->attempt($request->all());
        });

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        $iamport = new Iamport();

        $order = $order->refresh();

        // 내부 결제수단 (카드등록해놓은 빌링키 결제)
        if($order->payMethod->external == 0){
            $card = Card::find($request->card_id);

            $result = $iamport->payByBillingKey($order, $card);

            if(!$result['success'])
                return $this->respondForbidden($result['message']);

            $order->update(['imp_uid' => $result['data']['imp_uid']]);

            return $this->respondSuccessfully([
                'order' => OrderResource::make($order)
            ]);
        }

        return $this->respondSuccessfully([
            'order' => OrderResource::make($order),
            "m_redirect_url" => config("app.url")."/api/orders/complete/mobile",
            "imp_code" => config("iamport.imp_code"), // 가맹점 상점아이디
        ]);
    }

    /** 수정(주소지 변경 -> 배송비 계산용)
     * @group 사용자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function updateDelivery(Order $order, OrderRequest $request)
    {
        if(auth()->user() && $order->user_id != auth()->id())
            return $this->respondForbidden();

        if(!auth()->user() && $order->guest_id != $request->guest_id)
            return $this->respondForbidden();

        if($order->state != StateOrder::BEFORE_PAYMENT)
            return $this->respondForbidden('결제준비중인 주문건에 대해서만 처리할 수 있습니다.');

        $result = DB::transaction(function () use($order, $request){
            $order->update($request->validated());

            $presets = $order->presets;

            foreach($presets as $preset){
                $presetProducts = $preset->presetProducts;

                foreach($presetProducts as $presetProduct){
                    $presetProduct->update($request->validated());
                }

                $preset->update([
                    'price_delivery' => $preset->calculatePriceDelivery()
                ]);
            }
        });

        return $this->respondSuccessfully(new OrderResource($order));
    }

    // 결제검증(OrderObserver 사용)
    public function complete(OrderRequest $request)
    {
        $iamport = new Iamport();

        $path = $request->path();

        if(strpos($path, 'webhook') !== false)
            sleep(3); // 3초 대기 (중복방지)

        // 주문조회
        $result = $iamport->getOrder($request->imp_uid);

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        $impOrder = $result['data'];


        $order = Order::where(function($query){
            $query->where("state", StateOrder::WAIT)
                ->orWhere("state", StateOrder::BEFORE_PAYMENT);
        })->where("merchant_uid", $impOrder["merchant_uid"])
            ->first();


        DB::beginTransaction();

        try {
            if(!$order)
                return abort(404);

            if($order->price != $impOrder["amount"])
                return abort(403);

            switch ($impOrder["status"]){
                case "ready":
                    // 가상계좌 발급 (해당 부분 리뉴얼해야함)
                    /*$vbankNum = $impOrder["vbank_num"];
                    $vbankDate = Carbon::parse($impOrder["vbank_date"])->format("Y-m-d H:i");
                    $vbankName = $impOrder["vbank_name"];

                    // OrderObserver 사용
                    $order->update([
                        "transaction_id" => $impOrder['transaction_id'],
                        "state" => StateOrder::WAIT,
                        "vbank_num" => $vbankNum,
                        "vbank_date" => $vbankDate,
                        "vbank_name" => $vbankName
                    ]);

                    $result = ["success" => 1, "message"=>"가상계좌 발급이 완료되었습니다. ${vbankName}/ ${vbankNum} / ${vbankDate}"];

                    break;*/
                case "paid": // 결제완료
                    $order->update(["imp_uid" => $request->imp_uid, "state" => StateOrder::SUCCESS]);

                break;
            }

            DB::commit();
        }catch(\Exception $e) {
            // Iamport::cancel($accessToken, $request->imp_uid);
            $order->update(['reason' => $e->getMessage()]);

            Log::notice(json_encode($e->getMessage()));
            // $order->update(["state" => StateOrder::BEFORE_PAYMENT]);
            $result = ["success" => 0, "message"=> "결제를 실패하였습니다."];

            DB::rollBack();
        }

        $order = Order::where("imp_uid", $request->imp_uid)->first();

        // 모바일 결제 redirect가 필요할 경우
        if(strpos($request->path(), "mobile"))
            return redirect(config("app.client_url")."/orders/result?id={$order->id}");

        return $this->respondSuccessfully(OrderResource::make($order));
    }

    /** 회원용 상세
     * @group 사용자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function show(Order $order, Request $request)
    {
        if(auth()->user() && $order->user_id != auth()->id())
            return $this->respondForbidden();

        if(!auth()->user() && $order->guest_id != $request->guest_id)
            return $this->respondForbidden();

        return $this->respondSuccessfully(OrderResource::make($order));
    }

    /** 비회원용 상세
     * @group 사용자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function showByGuest(OrderRequest $request)
    {
        $request['buyer_contact'] = str_replace("-", "", $request->buyer_contact);

        $order = new Order();

        if($request->merchant_uid)
            $order = $order->where('merchant_uid', $request->merchant_uid)->first();
        else{
            $order = Order::where('buyer_contact', $request->buyer_contact)
                ->where('buyer_name', $request->buyer_name)
                ->where('state', '!=', StateOrder::BEFORE_PAYMENT)
                ->latest()
                ->first();
        }

        if(!$order)
            return $this->respondNotFound();

        return $this->respondSuccessfully(OrderResource::make($order));
    }

    /** 주문서 조회
     * @group 사용자
     * @subgroup Order(주문)
     */
    public function bill(OrderRequest $request)
    {
        $order = Order::where('merchant_uid', $request->merchant_uid)
            ->first();

        $accessToken = Iamport::getAccessToken();

        $result = Iamport::getBill($accessToken, $order->imp_uid);

        return $this->respondSuccessfully($result);
    }

}
