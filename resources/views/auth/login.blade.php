{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50 p-4">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-xl mb-4">
                {{-- SVG Icon cho chiếc lá (Leaf) thay thế cho lucide-react --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" color="#ffffff"
                    fill="none">
                    <path
                        d="M7.64584 15.7108C7.23279 14.8966 7 13.9755 7 13C7 9.78484 9.5 7.5 13 7C17.0817 6.4169 18.8333 4.16667 20 3C23.5 16 17 19 13 19C11.9071 19 10.8825 18.7078 10 18.1973"
                        stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3 21C3.5 18 5.45791 16.1355 10 15C13.2167 14.1958 15.4634 12.1791 17 10.0549"
                        stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </div>
            <h1 class="text-2xl text-gray-800 mb-2">EcoSchool</h1>
            <p class="text-gray-500">Hệ thống Quản lý Rác thải Trường học</p>
        </div>

        {{-- Hiển thị lỗi chung --}}
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf {{-- Thêm token CSRF bảo mật --}}

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" placeholder="admin@school.edu" value="{{ old('email') }}"
                    {{-- Giữ lại email đã nhập nếu có lỗi --}} required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                @error('email')
                {{-- Hiển thị lỗi validation cho email --}}
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm font-medium text-gray-700">Mật khẩu</label>
                <div class="relative">
                    <input id="password" name="password" type="password" placeholder="••••••••" required
                        class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                            color="#000000" fill="none">
                            <path
                                d="M21.544 11.045C21.848 11.4713 22 11.6845 22 12C22 12.3155 21.848 12.5287 21.544 12.955C20.1779 14.8706 16.6892 19 12 19C7.31078 19 3.8221 14.8706 2.45604 12.955C2.15201 12.5287 2 12.3155 2 12C2 11.6845 2.15201 11.4713 2.45604 11.045C3.8221 9.12944 7.31078 5 12 5C16.6892 5 20.1779 9.12944 21.544 11.045Z"
                                stroke="#141B34" stroke-width="1.5" />
                            <path
                                d="M15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12Z"
                                stroke="#141B34" stroke-width="1.5" />
                        </svg>
                    </button>
                </div>
                @error('password')
                {{-- Hiển thị lỗi validation cho password --}}
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember"
                        class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" />
                    <span class="text-gray-600">Ghi nhớ đăng nhập</span>
                </label>
                <a href="{{ route('forgot_password.form') }}" class="text-green-600 hover:underline">
                    Quên mật khẩu?
                </a>
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Đăng nhập
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>Demo: admin@school.edu / password</p>
        </div>

        <div class="mt-4 text-center text-sm">
            <span class="text-gray-600">Chưa có tài khoản? </span>
            <a href="{{ route('register') }}" class="font-medium text-green-600 hover:underline">
                Đăng ký ngay
            </a>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");

    togglePassword.addEventListener("click", () => {
        const isHidden = passwordInput.type === "password";
        passwordInput.type = isHidden ? "text" : "password";
        updateIcon(passwordInput.type, togglePassword);
    });

    function updateIcon(type, icon) {
        if (type === "password") {
            icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                            color="#000000" fill="none">
                            <path
                                d="M21.544 11.045C21.848 11.4713 22 11.6845 22 12C22 12.3155 21.848 12.5287 21.544 12.955C20.1779 14.8706 16.6892 19 12 19C7.31078 19 3.8221 14.8706 2.45604 12.955C2.15201 12.5287 2 12.3155 2 12C2 11.6845 2.15201 11.4713 2.45604 11.045C3.8221 9.12944 7.31078 5 12 5C16.6892 5 20.1779 9.12944 21.544 11.045Z"
                                stroke="#141B34" stroke-width="1.5" />
                            <path
                                d="M15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12Z"
                                stroke="#141B34" stroke-width="1.5" />
                        </svg>`
        } else if (type === "text") {
            icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000" fill="none">
    <path d="M19.439 15.439C20.3636 14.5212 21.0775 13.6091 21.544 12.955C21.848 12.5287 22 12.3155 22 12C22 11.6845 21.848 11.4713 21.544 11.045C20.1779 9.12944 16.6892 5 12 5C11.0922 5 10.2294 5.15476 9.41827 5.41827M6.74742 6.74742C4.73118 8.1072 3.24215 9.94266 2.45604 11.045C2.15201 11.4713 2 11.6845 2 12C2 12.3155 2.15201 12.5287 2.45604 12.955C3.8221 14.8706 7.31078 19 12 19C13.9908 19 15.7651 18.2557 17.2526 17.2526" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M9.85786 10C9.32783 10.53 9 11.2623 9 12.0711C9 13.6887 10.3113 15 11.9289 15C12.7377 15 13.47 14.6722 14 14.1421" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" />
    <path d="M3 3L21 21" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>`
        }
    }
</script>
@endsection