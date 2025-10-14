{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50 p-4">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-xl mb-4">
                {{-- SVG Icon cho chiếc lá (Leaf) thay thế cho lucide-react --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 4 13H2a10 10 0 0 0 10 10z" />
                    <path d="M12 2a7 7 0 0 1 7 7h2a10 10 0 0 0-10-10z" />
                    <path d="M12 22a10 10 0 0 0 10-10h-2a7 7 0 0 1-7 7z" />
                    <path d="M2 12a10 10 0 0 0 10 10v-2a7 7 0 0 1-7-7z" />
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
            @csrf  {{-- Thêm token CSRF bảo mật --}}

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="admin@school.edu"
                    value="{{ old('email') }}" {{-- Giữ lại email đã nhập nếu có lỗi --}}
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                />
                @error('email') {{-- Hiển thị lỗi validation cho email --}}
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm font-medium text-gray-700">Mật khẩu</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                />
                 @error('password') {{-- Hiển thị lỗi validation cho password --}}
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" />
                    <span class="text-gray-600">Ghi nhớ đăng nhập</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-green-600 hover:underline">
                    Quên mật khẩu?
                </a>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
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
@endsection