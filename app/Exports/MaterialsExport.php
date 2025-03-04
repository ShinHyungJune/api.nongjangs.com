<?php

namespace App\Exports;

use App\Enums\DeliveryCompany;
use App\Enums\TypeMaterial;
use App\Enums\TypePackageMaterial;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaterialsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
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
            $item['type'] ? TypePackageMaterial::getLabel($item['type']) : '',
            $item['title']." ".$item['value'].$item['unit'],
            $item['count'],
        ];

        return $result;
    }

    public function headings(): array
    {
        $result = [
            "유형",
            "상품명",
            "개수",
        ];

        return $result;
    }
}
