<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token không được cung cấp.'], 401);
        }

        try {
            $secret = env('APP_KEY', 'your-secret-key-here');
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            
            $user = User::find($decoded->sub);
            
            if (!$user) {
                return response()->json(['error' => 'Người dùng không tồn tại.'], 404);
            }

            // Gắn user vào request để các controller sử dụng
            $request->attributes->add(['auth_user' => $user]);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json(['error' => 'Token đã hết hạn.'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token không hợp lệ.'], 401);
        }

        return $next($request);
    }
}
