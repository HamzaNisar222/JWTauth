<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Models\ActiveToken;
use App\Models\BlacklistedToken;

class CheckBlacklistMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Retrieve the token from the request
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 400);
            }
            if (BlacklistedToken::where('token', $token)->exists()) {
                return response()->json(['error' => 'Token is blacklisted'], 401);
            }

            return $next($request);
        } catch (TokenExpiredException $e) {

            $token = JWTAuth::getToken();
            if ($token) {
                ActiveToken::where('token', $token)->delete();
            }
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token validation failed'], 401);
        }
    }
}
