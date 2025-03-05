<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\StateOrder;
use App\Enums\StatePresetProduct;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Models\Order;
use App\Models\PresetProduct;
use App\Models\Qna;
use App\Models\Review;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends ApiController
{

    /** 대시보드 차트 내용
     * @group 관리자
     * @subgroup Dashboard(대시보드)
     * @responseFile storage/responses/matrix.json
     */
    public function matrix(Request $request)
    {
        $request['year'] = $request->year ?? Carbon::now()->year;
        $request['month'] = $request->year ?? Carbon::now()->month;

        $item = [
            'count_preset_product_ready' => PresetProduct::where('state', StatePresetProduct::READY)->count(),
            'count_preset_product_wait' => PresetProduct::where('state', StatePresetProduct::WAIT)->count(),
            'count_preset_product_request_cancel' => PresetProduct::where('state', StatePresetProduct::REQUEST_CANCEL)->count(),
            'count_qna' => Qna::whereNull('answer')->count(),
            'count_review' => Review::whereNull('reply')->count(),

            'visits' => [
                'days' => $this->getVisits('days'),
                'months' => $this->getVisits('months'),
            ],

            'totalCounts' => $this->getTotalCounts(Carbon::now()->setYear($request->year)->setMonth($request->month)),
        ];

        return $this->respondSuccessfully($item);
    }

    public function getTotalCounts($date)
    {
        $startDate = (clone $date->startOfMonth())->format('d');
        $finishDate = (clone $date->endOfMonth())->format('d');

        $items = [];

        for($i = $startDate; $i<=$finishDate; $i++){
            $date = (clone $date)->setDate($i);

            $items[] = [
                'title' => $date->format('y-m-d'),
                'count_order' => Order::where('state', '!=' , StateOrder::BEFORE_PAYMENT)
                    ->where('created_at', '>=', (clone $date)->startOfDay())
                    ->where('created_at', '<=', (clone $date)->endOfDay())
                    ->count(),
                'sum_order_price' => Order::where('state', '!=' , StateOrder::BEFORE_PAYMENT)
                    ->where('created_at', '>=', (clone $date)->startOfDay())
                    ->where('created_at', '<=', (clone $date)->endOfDay())
                    ->sum('price'),
                'count_visit' => $this->getVisitByDay((clone $date)),
                'count_user_create' => User::where('created_at', '>=', (clone $date)->startOfDay())
                    ->where('created_at', '<=', (clone $date)->endOfDay())
                    ->count(),
                'count_order_package' => Order::where('state', '!=' , StateOrder::BEFORE_PAYMENT)
                    ->whereHas('presetProducts', function ($query){
                        $query->whereNotNull('package_id');
                    })
                    ->where('created_at', '>=', (clone $date)->startOfDay())
                    ->where('created_at', '<=', (clone $date)->endOfDay())
                    ->count(),
                'count_order_product' => Order::where('state', '!=' , StateOrder::BEFORE_PAYMENT)
                    ->whereHas('presetProducts', function ($query){
                        $query->whereNotNull('product_id');
                    })
                    ->where('created_at', '>=', (clone $date)->startOfDay())
                    ->where('created_at', '<=', (clone $date)->endOfDay())
                    ->count(),
                'count_preset_product_cancel' => PresetProduct::where('state', '=' , StatePresetProduct::REQUEST_CANCEL)
                    ->where('created_at', '>=', (clone $date)->startOfDay())
                    ->where('created_at', '<=', (clone $date)->endOfDay())
                    ->count(),
            ];
        }

        return $items;
    }

    public function getVisits($type = "days")
    {
        $items = [];

        if($type === 'days'){
            for($i=6; $i>=0; $i--){
                $items[] = $this->getVisitByDay(Carbon::now()->subDays($i));
            }
        }

        if($type === 'months'){
            for($i=12; $i>=0; $i--){
                $items[] = $this->getVisitByMonth(Carbon::now()->subMonths($i));
            }
        }

        return $items;
    }

    public function getVisitByDay($date)
    {
        return [
            'count' => Visit::where('created_at', '>=', (clone $date)->startOfDay())
                ->where('created_at', '<=', (clone $date)->endOfDay())
                ->count(),
            'title' => $date->format('m.d') . '(' . __('date.' . $date->isoFormat('ddd')) . ')',
        ];
    }

    public function getVisitByMonth($date)
    {
        return [
            'count' => Visit::where('created_at', '>=', (clone $date)->startOfMonth()->startOfDay())
                ->where('created_at', '<=', (clone $date)->endOfMonth()->endOfDay())
                ->count(),
            'title' => $date->format('m.d') . '(' . __('date.' . $date->isoFormat('ddd')) . ')',
        ];
    }
}
