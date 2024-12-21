<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class EmailVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user() && auth()->user()->verified_at) {
            return $next($request);
        }

        session()->put('url.intended', url()->current());

        return response()->json([
            "message" => __("comment.emailVerifications.403"),
            'status_code' => 403
        ], 403);
    }
}
