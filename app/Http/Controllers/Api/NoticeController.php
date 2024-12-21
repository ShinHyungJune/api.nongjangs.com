<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoticeResource;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends ApiController
{
    public function index(Request $request)
    {
        $items = new Notice();

        $items = $items->orderBy('important', 'desc')->latest()->paginate(12);

        return NoticeResource::collection($items);
    }

    public function show(Notice $notice)
    {
        $notice->update([
            'count_view' => $notice->count_view + 1
        ]);

        return $this->respondSuccessfully(NoticeResource::make($notice));
    }

}
