<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('api')->check()) {
            return Helper::jsonErrorResponse('Unauthorized', 401);
        }
        if (Auth::guard('api')->user()->role !== 'admin') {
            return Helper::jsonErrorResponse('Only administrators can perform this action', 403);
        }

        return $next($request);
    }
}
