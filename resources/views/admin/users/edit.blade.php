@extends('dashboard.admin')

@section('dashboard-content')
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.users.index') }}" class="hover:text-green-600">Quản lý người dùng</a>
                <span>/</span>
                <span class="text-gray-900">Chỉnh sửa người dùng</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa người dùng</h1>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
            <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Tên -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Tên người dùng <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vai trò -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Vai trò <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('role') border-red-500 @enderror">
                        <option value="">-- Chọn vai trò --</option>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Người dùng
                        </option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Quản trị viên
                        </option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mật khẩu mới (tùy chọn) -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mật khẩu mới <span class="text-gray-500">(Để trống nếu không đổi)</span>
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Mật khẩu phải có ít nhất 8 ký tự</p>
                </div>

                <!-- Xác nhận mật khẩu mới -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Xác nhận mật khẩu mới
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Thông tin bổ sung -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <strong>Loại tài khoản:</strong>
                        @if ($user->isLocal())
                            <span class="text-gray-900">Local</span>
                        @elseif($user->isGoogle())
                            <span class="text-red-600">Google</span>
                        @else
                            <span class="text-blue-600">Facebook</span>
                        @endif
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Ngày tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Cập nhật lần cuối:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Cập nhật
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

