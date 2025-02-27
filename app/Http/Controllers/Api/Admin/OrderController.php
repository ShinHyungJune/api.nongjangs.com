<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StateOrder;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends ApiController
{


    /** 목록
     * @group 관리자
     * @subgroup Order(주문)
     * @responseFile storage/responses/orders.json
     */
    public function index(OrderRequest $request)
    {
        $items = Order::where('state', '!=', StateOrder::BEFORE_PAYMENT);

        if($request->started_at)
            $items = $items->where('created_at', '>=', Carbon::make($request->started_at)->startOfDay());

        if($request->finished_at)
            $items = $items->where('created_at', '<=', Carbon::make($request->finished_at)->endOfDay());

        if($request->word)
            $items = $items->where('buyer_name', 'LIKE', '%'.$request->word.'%')
                ->orWhere('buyer_contact', 'LIKE', '%'.$request->word.'%');

        if($request->state)
            $items = $items->where('state', $request->state);

        $items = $items->latest()->paginate(10);

        return OrderResource::collection($items);
    }

    /** 상세
     * @group 관리자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function show(Order $order)
    {
        return $this->respondSuccessfully(OrderResource::make($order));
    }

    /** 생성
     * @group 관리자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function store(OrderRequest $request)
    {
        $createdItem = Order::create($request->all());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $createdItem->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(OrderResource::make($createdItem));
    }

    /** 수정
     * @group 관리자
     * @subgroup Order(주문)
     * @responseFile storage/responses/order.json
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order->update($request->all());

        if($request->files_remove_ids){
            $medias = $order->getMedia("img");

            foreach($medias as $media){
                foreach($request->files_remove_ids as $id){
                    if((int) $media->id == (int) $id){
                        $media->delete();
                    }
                }
            }
        }

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $order->addMedia($file["file"])->toMediaCollection("img", "s3");
            }
        }

        return $this->respondSuccessfully(OrderResource::make($order));
    }

    /** 삭제
     * @group 관리자
     * @subgroup Order(주문)
     */
    public function destroy(OrderRequest $request)
    {
        Order::whereIn('id', $request->ids)->delete();

        return $this->respondSuccessfully();
    }

    public function cancel(Order $order)
    {
        if(!$order->admin_can_cancel)
            return $this->respondForbidden('취소가 불가능한 주문건입니다.');

        $result = $order->cancel();

        if(!$result['success'])
            return $this->respondForbidden($result['message']);

        return $this->respondSuccessfully();
    }
}
