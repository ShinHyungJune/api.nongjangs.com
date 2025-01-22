<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{

    /**
     * @group Category(카테고리)
     * @responseFile storage/responses/categories.json
     */
    public function index()
    {
        $categories = Category::orderBy('order', 'asc')->paginate(30);

        return CategoryResource::collection($categories);
    }
}
