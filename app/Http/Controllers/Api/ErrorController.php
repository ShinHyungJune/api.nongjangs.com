<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ErrorController extends Controller
{
    public function notFound()
    {
        return Inertia::render("Errors/404");
    }

    public function unAuthenticated()
    {
        return Inertia::render("Errors/403");
    }
}
