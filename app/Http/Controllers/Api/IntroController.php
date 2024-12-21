<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IntroResource;
use App\Models\Intro;
use Illuminate\Http\Request;

class IntroController extends Controller
{
    /**
     * @group Intro(회사소개)
     * @responseFile storage/responses/intros.json
     */
    public function index()
    {
        $items = Intro::where('use', 1)->paginate(30);

        return IntroResource::collection($items);
    }
}
