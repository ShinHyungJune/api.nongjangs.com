<?php

namespace App\Http\Controllers\Api;

use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Iamport;
use App\Models\Order;
use App\Models\PayMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if($order->state != StateOrder::BEFORE_PAYMENT)
            return $this->respondForbidden('결제준비중인 주문건에 대해서만 결제요청을 할 수 있습니다.');

        $result = DB::transaction(function () use($order, $request){
            return $order->attempt($request->all());
        });

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully([
            'order' => OrderResource::make($order),
            "m_redirect_url" => config("app.url")."/api/orders/complete/mobile",
            "imp_code" => config("iamport.imp_code"), // 가맹점 식별코드
        ]);
    }

    // 결제검증(OrderObserver 사용)
    public function complete(OrderRequest $request)
    {
        $path = $request->path();

        if(strpos($path, 'webhook') !== false)
            sleep(3); // 3초 대기 (중복방지)

        // 권한 얻기
        $accessToken = Iamport::getAccessToken();

        // 주문조회
        $impOrder = Iamport::getOrder($accessToken, $request->imp_uid);

        $order = Order::where(function($query){
            $query->where("state", StateOrder::WAIT)
                ->orWhere("state", StateOrder::BEFORE_PAYMENT);
        })->where("merchant_uid", $impOrder["merchant_uid"])->first();

        DB::beginTransaction();

        try {
            if(!$order)
                return abort(404);

            if($order->price != $impOrder["amount"])
                return abort(403);

            switch ($impOrder["status"]){
                case "ready": // 가상계좌 발급
                    $vbankNum = $impOrder["vbank_num"];
                    $vbankDate = Carbon::parse($impOrder["vbank_date"])->format("Y-m-d H:i");
                    $vbankName = $impOrder["vbank_name"];

                    // OrderObserver 사용
                    $order->update([
                        "imp_uid" => $request->imp_uid,
                        "state" => StateOrder::WAIT,
                        "vbank_num" => $vbankNum,
                        "vbank_date" => $vbankDate,
                        "vbank_name" => $vbankName
                    ]);

                    $result = ["success" => 1, "message"=>"가상계좌 발급이 완료되었습니다. ${vbankName}/ ${vbankNum} / ${vbankDate}"];

                    break;
                case "paid": // 결제완료
                    // OrderObserver 사용
                    $order->update(["imp_uid" => $request->imp_uid, "state" => StateOrder::SUCCESS]);

                    // 맞춤결제는 주문성공하자마자 주문확정처리 (시안작성, 배송단계가 없음)
                    if($order->isCustomOrder()){
                        $order->presetProducts()->update([
                            'state' => StatePresetProduct::CONFIRMED
                        ]);
                    }

                    $result = ["success" => 1, "message"=> "결제가 완료되었습니다."];

                    break;
            }

            DB::commit();
        }catch(\Exception $e) {
            // Iamport::cancel($accessToken, $request->imp_uid);
            $order->update(['reason' => $e->getMessage()]);

            // $order->update(["state" => StateOrder::BEFORE_PAYMENT]);
            $result = ["success" => 0, "message"=> "결제를 실패하였습니다."];

            DB::rollBack();
        }

        $order = Order::where("merchant_uid", $request->merchant_uid)->first();

        // 모바일 결제 redirect가 필요할 경우
        if(strpos($request->path(), "mobile"))
            return redirect(config("app.client_url")."/orders/result?merchant_uid={$order->merchant_uid}&buyer_contact={$order->buyer_contact}&buyer_name={$order->buyer_name}");

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
