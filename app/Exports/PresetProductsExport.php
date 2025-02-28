<?php

namespace App\Exports;

use App\Enums\DeliveryCompany;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PresetProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function map($item) :array
    {
        $user = User::withTrashed()->find($item->preset->user_id);

        $result = [
            $item->id,
            $item->format_state,
            $item->product ? $item->product->uuid : '-',
            $item->preset->order->payment_id,
            $item->preset->order->success_at,
            $item->delivery_company ? DeliveryCompany::getLabel($item->delivery_company) : '',
            $item->delivery_number,
            $item->delivery_name,
            $item->delivery_contact,
            "(".$item->delivery_address_zipcode.")"." ".$item->delivery_address." ".$item->delivery_address_detail,
            $item->delivery_requirement,
            $item->format_title,
            $item->count ?? 1,
            /*$user->email,
            $user->name,
            $user->contact,*/
            $user->price,
        ];

        return $result;
    }

    public function headings(): array
    {
        $result = [
            "출고번호", // 0
            "상태",
            "상품번호", // 2
            "주문번호",
            "결제일시", // 4
            "택배사",
            "운송장번호", // 6
            "수령인",
            "수령인 연락처",
            "주소",
            "배송요청사항",
            "상품명",
            "개수",
            "결제금액",
        ];

        return $result;
    }
}
