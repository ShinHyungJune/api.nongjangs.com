<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstimateRequest;
use App\Http\Resources\EstimateResource;
use App\Models\Estimate;
use Illuminate\Http\Request;


class EstimateController extends ApiController
{
    /**
     * @group Estimate(견적)
     * @priority 2
     */
    public function store(EstimateRequest $request)
    {
        $estimate = Estimate::create($request->validated());

        if(is_array($request->file("files"))){
            foreach($request->file("files") as $file){
                $estimate->addMedia($file["file"])->toMediaCollection("files", "s3");
            }
        }

        return $this->respondSuccessfully();
    }
}
