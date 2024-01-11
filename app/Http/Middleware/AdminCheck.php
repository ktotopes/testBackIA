<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->isAdmin()){
            Auth::logout();

            abort(403);
        }

        if (!$request->routeIs('admin.orders.*')) {
            return to_route('admin.orders.index');
        }

        return $next($request);
    }
}
