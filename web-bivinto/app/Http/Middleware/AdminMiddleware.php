<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem User đã đăng nhập chưa và có phải admin không
        if (!auth('api')->check() || !auth('api')->user()->isAdmin()) {
            // Nếu là request API thì trả về JSON lỗi 403
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Bạn không có quyền truy cập.'], 403);
            }
            // Nếu là request trên giao diện Web thì redirect về trang chủ kèm lỗi
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}
