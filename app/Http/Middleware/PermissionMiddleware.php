<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RolePermission;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Kiểm tra user có permission cụ thể không
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập trang này.');
        }

        $user = auth()->user();
        
        // Admin có tất cả quyền
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Kiểm tra permission
        if (!$user->hasPermission($permission)) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        return $next($request);
    }
}
