<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class AccessTokenIsValid
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        // dd($request->header('authorization'));
        if ($request->header('authorization') == NULL) {
            return $this->sendResponse(
                'Please enter access token.',
                422
            );
        }
        if (!Auth::guard('api')->check()) {
            return $this->sendResponse('User not found.',401);
        }
        if (Auth::guard('api')->check()) {
            // dd(Auth::guard('api')->user());
            if (Auth::guard('api')->user()->role == 2) {
                return $this->sendResponse(
                    'You have not access',
                    401
                );
            }
        }
        return $next($request);
    }
}
