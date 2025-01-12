<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
