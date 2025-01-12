<?php

namespace App\Http\Controllers\Api\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
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
        if(auth()->user() && auth()->user()->admin) {
            return $next($request);
        }

        session()->put('url.intended', url()->current());

        return response()->json([
            "message" => '권한이 없습니다.',
            'status_code' => 401
        ], 401);
    }
}
