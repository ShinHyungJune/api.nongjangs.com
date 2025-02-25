<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountResource;
use App\Http\Requests\CountRequest;
use App\Models\Count;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CountController extends ApiController
{
    /** 상세
     * @group 관리자
     * @subgroup Count(누적농산물_농사수)
     * @responseFile storage/responses/counts.json
     */
    public function show(Count $count)
    {
        return $this->respondSuccessfully(CountResource::make($count));
    }

    /** 생성
     * @group 관리자
     * @subgroup Count(누적농산물_농사수)
     * @responseFile storage/responses/counts.json
     */
    public function store(CountRequest $request)
    {
        $count = Count::first();

        if(!$count)
            $count = Count::create($request->validated());
        else
            $count = $count->update($request->validated());

        return $this->respondSuccessfully(CountResource::make($count));
    }
}
