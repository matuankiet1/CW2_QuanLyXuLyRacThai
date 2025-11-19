<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Hiển thị trang quản lý phân quyền
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $adminCount = User::where('role', 'admin')->count();
        $userCount = User::where('role', 'user')->count();
        
        return view('admin.roles.index', compact('users', 'adminCount', 'userCount'));
    }

    /**
     * Cập nhật role của user
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:admin,manager,staff,student'],
        ]);
        
        // Kiểm tra quyền: chỉ admin mới có thể thay đổi role
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Bạn không có quyền thực hiện hành động này!'
            ]);
        }

        // Không cho phép admin tự thay đổi role của mình
        if ($user->user_id === Auth::id()) {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Bạn không thể thay đổi quyền của chính mình!'
            ]);
        }

        // Không cho phép thay đổi role của super admin
        if ($user->email === 'admin@ecowaste.com') {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Không thể thay đổi quyền của Super Admin!'
            ]);
        }

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        $message = $request->role === 'admin' 
            ? "Đã cấp quyền admin cho {$user->name}" 
            : "Đã thu hồi quyền admin của {$user->name}";

        return back()->with('status', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    /**
     * Tạo tài khoản admin mới
     */
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:15'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Kiểm tra quyền: chỉ admin mới có thể tạo admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Bạn không có quyền tạo tài khoản admin!'
            ]);
        }

        User::create([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'phone' => $request->phone ? trim($request->phone) : null,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'auth_provider' => 'local',
            'email_verified_at' => now(),
        ]);

        return back()->with('status', [
            'type' => 'success',
            'message' => "Đã tạo tài khoản admin cho {$request->name} thành công!"
        ]);
    }

    /**
     * Xóa tài khoản (chỉ user, không được xóa admin)
     */
    public function destroy(User $user)
    {
        
        // Kiểm tra quyền
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Bạn không có quyền xóa tài khoản!'
            ]);
        }

        // Không cho phép xóa chính mình
        if ($user->user_id === Auth::id()) {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Bạn không thể xóa tài khoản của chính mình!'
            ]);
        }

        // Không cho phép xóa super admin
        if ($user->email === 'admin@ecowaste.com') {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Không thể xóa tài khoản Super Admin!'
            ]);
        }

        // Không cho phép xóa admin khác
        if ($user->role === 'admin') {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Không thể xóa tài khoản admin!'
            ]);
        }

        $user->delete();

        return back()->with('status', [
            'type' => 'success',
            'message' => "Đã xóa tài khoản {$user->name} thành công!"
        ]);
    }
}
