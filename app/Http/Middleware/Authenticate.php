<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (request()->header('Authorization')) {
            $item = Token::where('token', request()->header('Authorization'))->first();
            if ($guard == 'user' && $item && $item->user_id) return $next($request);
            elseif ($guard == 'admin' && $item && $item->admin_id) return $next($request);
            elseif ($guard == 'doctor' && $item && $item->doctor_id) return $next($request);
            elseif ($guard == 'clinic' && $item && $item->clinic_id) return $next($request);
        }
        $response = array(
            'default_response' => [
                'message' => __('api.Authorization'),
                'errors' => null
            ],
        );
        return response()->json($response, 401);
    }
}
