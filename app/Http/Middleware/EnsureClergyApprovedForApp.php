<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureClergyApprovedForApp
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('api')->user();
        if (! $user || $user->role !== 'clergy') {
            return Helper::jsonErrorResponse('Unauthorized', 401);
        }

        $clergy = $user->clergy;
        if (! $clergy || ($clergy->registration_status ?? 'approved') !== 'approved') {
            return Helper::jsonErrorResponse(
                'Your clergy profile is pending admin approval or was rejected. You cannot use clergy features yet.',
                403
            );
        }

        return $next($request);
    }
}
