<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerResource;
use App\Http\Resources\NoticeResource;
use App\Models\Banner;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $items = new User();

        $items = $items->latest()->paginate(3000);

        return view("app", [
            "items" => $items
        ]);
    }

    public function intro()
    {
        return Inertia::render("Contents/Intro");
    }

    public function about()
    {
        return Inertia::render("Contents/About");
    }

    public function privacyPolicy()
    {
        return Inertia::render("Contents/PrivacyPolicy");
    }
}
