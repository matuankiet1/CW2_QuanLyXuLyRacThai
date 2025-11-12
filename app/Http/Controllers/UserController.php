<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng với phân trang và tìm kiếm
     * 
     * Route: GET /admin/users
     * 
     * Chức năng:
     * - Lấy danh sách người dùng từ database
     * - Phân trang 10 người dùng mỗi trang
     * - Sắp xếp theo ngày tạo (created_at) giảm dần (mới nhất trước)
     * - Hỗ trợ tìm kiếm theo tên và email (nếu có từ khóa)
     * - Giữ lại từ khóa tìm kiếm khi chuyển trang
     * 
     * @param Request $request - Request chứa từ khóa tìm kiếm (keyword)
     * @return \Illuminate\View\View - View hiển thị danh sách người dùng
     */
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm và role filter từ request (nếu có)
        $keyword = $request->input('keyword');
        $roleFilter = $request->input('role', 'all');
        
        // Bắt đầu query để lấy danh sách người dùng
        $query = User::query();
        
        // Nếu có từ khóa tìm kiếm, thêm điều kiện tìm kiếm vào query
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                // Tìm kiếm theo tên (name) - không phân biệt hoa thường
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                  // Hoặc tìm kiếm theo email - không phân biệt hoa thường
                  ->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        }
        
        // Lọc theo role nếu được chọn
        if ($roleFilter && $roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }
        
        // Sắp xếp theo ngày tạo giảm dần (mới nhất trước)
        // Phân trang 10 người dùng mỗi trang
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Thêm các tham số vào link phân trang để giữ lại khi chuyển trang
        if ($keyword || ($roleFilter && $roleFilter !== 'all')) {
            $users->appends([
                'keyword' => $keyword,
                'role' => $roleFilter
            ]);
        }
        
        // Trả về view với dữ liệu người dùng, từ khóa tìm kiếm và role filter
        return view('admin.users.index', compact('users', 'keyword', 'roleFilter'));
    }

    /**
     * Hiển thị form tạo người dùng mới
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu người dùng mới vào database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'auth_provider' => 'local',
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Thêm người dùng thành công!'
            ]);
    }

    /**
     * Hiển thị chi tiết người dùng
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Cập nhật người dùng thành công!'
            ]);
    }

    /**
     * Xóa người dùng
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép xóa chính mình
        if ($user->user_id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('status', [
                    'type' => 'error',
                    'message' => 'Không thể xóa tài khoản của chính bạn!'
                ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', [
                'type' => 'success',
                'message' => 'Xóa người dùng thành công!'
            ]);
    }
}
