<?php

namespace App\Exports;

use App\Enums\DeliveryCompany;
use App\Enums\StatePresetProduct;
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
    protected $hasSubscriptionProducts = false;

    public function __construct($items)
    {
        $this->items = $items;

        // Check if any items are subscription products
        foreach ($items as $item) {
            if ($item->package_id) {
                $this->hasSubscriptionProducts = true;
                break;
            }
        }
    }

    public function collection()
    {
        return $this->items;
    }

    public function map($item) :array
    {
        $user = User::withTrashed()->find($item->preset->user_id ?? null);

        // 공통 필드
        $result = [
            $item->state ? StatePresetProduct::getLabel($item->state) : '',                           // 주문상태
            $item->product ? ($item->product->uuid ?? '-') : '-',                                     // 상품번호
            $item->preset && $item->preset->order ? ($item->preset->order->merchant_uid ?? '') : '',  // 주문번호
            $item->preset && $item->preset->order ? ($item->preset->order->success_at ?? '') : '',    // 주문일시
            $item->delivery_company ? DeliveryCompany::getLabel($item->delivery_company) : '',        // 택배사
            $item->delivery_number ?? '',                                                             // 송장번호
            $user && $user->grade ? ($user->grade->title ?? '') : '',                                 // 등급
            $item->delivery_name ?? '',                                                               // 수령인
            $item->delivery_contact ?? '',                                                            // 수령인 연락처
            "(".($item->delivery_address_zipcode ?? '').")"." ".($item->delivery_address ?? '')." ".($item->delivery_address_detail ?? ''), // 수령인 주소
            $item->delivery_requirement ?? '',                                                        // 배송메세지
            $item->format_title ?? '',                                                                // 옵션
            $item->count ?? 1,                                                                        // 상품갯수
            $user ? ($user->email ?? '') : '',                                                        // 회원아이디
            $user ? ($user->name ?? '') : '',                                                         // 회원명
            $user ? ($user->contact ?? '') : '',                                                      // 회원 전화번호
            $item->product ? ($item->product_title ?? '') : ($item->package_name ?? ''),              // 상품명
        ];

        // 정기구독 상품인 경우 (package_id가 있는 경우)에만 회차와 품목 추가
        if ($item->package_id && $this->hasSubscriptionProducts) {
            // 회차(고객)
            $result[] = $item->package_count ?? '';

            // 품목 (Materials의 title과 count)
            $materialsText = '';
            if ($item->materials && count($item->materials) > 0) {
                foreach ($item->materials as $material) {
                    if ($material) {
                        $materialsText .= ($material->title ?? '') . ' ' . ($material->pivot->count ?? '0') . '개' . "\n";
                    }
                }
            }
            $result[] = rtrim($materialsText);
        } else if ($this->hasSubscriptionProducts) {
            // 직거래 상품이지만 정기구독 상품이 포함된 엑셀에서는 빈 값 추가
            $result[] = '';
            $result[] = '';
        }

        // 공통 필드 (계속)
        $result[] = $item->price ?? 0;                                                                // 결제금액
        $result[] = $item->preset && $item->preset->order ? ($item->preset->order->pay_method_name ?? '') : ''; // 결제수단
        $result[] = $item->preset && $item->preset->order ? ($item->preset->order->success_at ?? '') : '';     // 결제일시
        $result[] = $item->package_id ? '정기구독' : '직거래상품';                                              // 구매유형
        $result[] = $item->delivery_started_at ?? '';                                                 // 발송처리일
        $result[] = $item->delivery_finished_at ?? '';                                                // 배송완료일

        return $result;
    }

    public function headings(): array
    {
        $result = [
            "주문상태",
            "상품번호",
            "주문번호",
            "주문일시",
            "택배사",
            "송장번호",
            "등급",
            "수령인",
            "수령인 연락처",
            "수령인 주소",
            "배송메세지",
            "옵션",
            "상품갯수",
            "회원아이디",
            "회원명",
            "회원 전화번호",
            "상품명",
        ];

        // 정기구독 상품이 있는 경우에만 회차와 품목 헤더 추가
        if ($this->hasSubscriptionProducts) {
            $result[] = "회차(고객)";
            $result[] = "품목";
        }

        $result = array_merge($result, [
            "결제금액",
            "결제수단",
            "결제일시",
            "구매유형",
            "발송처리일",
            "배송완료일",
        ]);

        return $result;
    }
}
