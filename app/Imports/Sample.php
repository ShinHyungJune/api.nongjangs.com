<?php

namespace App\Imports;

use App\Models\CarCreator;
use App\Models\CarModel;
use App\Models\Coupon;
use App\Models\Department;
use App\Models\MessageHistory;
use App\Models\Report;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Sample implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model($row)
    {
        $coupon = Coupon::where("uuid", $row[0])->first();

        if(!$coupon){
            Coupon::create([
                "uuid" => $row[0],
            ]);
        }
    }
}
