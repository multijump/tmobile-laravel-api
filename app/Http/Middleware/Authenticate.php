<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
                if ($user && $user->code != null) {
                    $user->code = null;
                    $user->save();
                }

                return $next($request);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'message' => "Invalid access"
                    ]
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => "Invalid access"
                ]
            ], 401);
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
