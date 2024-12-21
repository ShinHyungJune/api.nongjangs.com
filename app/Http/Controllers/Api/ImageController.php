<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends ApiController
{
    public function store(Request $request)
    {
        $image = Image::create();

        $media = $image->addMedia($request->file("file"))->toMediaCollection("file", "s3");

        return $this->respond($media->getFullUrl());
    }
}
