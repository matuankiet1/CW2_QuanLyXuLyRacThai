<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     * Chỉ cho phép admin và manager truy cập
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập trang này.');
        }

        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'manager'])) {
            return redirect()->route('user.posts.home')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
