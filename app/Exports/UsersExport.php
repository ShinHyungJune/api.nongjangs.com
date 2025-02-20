<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
        $result = [
            $item->email,
            $item->name."({$item->nickname})",
            $item->grade ? "Lv.".$item->grade->level : '',
            number_format($item->total_order_price),
            number_format($item->point),
            $item->count_coupon." / ".$item->count_total_coupon,
            $item->agree_promotion ? 'Y' : 'N',
            Carbon::make($item->created_at)->format('Y-m-d'),
        ];

        return $result;
    }

    public function headings(): array
    {
        $result = [
            "이메일",
            "이름(닉네임)",
            "등급",
            "누적 결제금액",
            "적립금",
            "쿠폰",
            "마케팅",
            "가입일",
        ];

        return $result;
    }
}
