@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50 p-4">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-xl mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" color="#ffffff"
                    fill="none">
                    <path
                        d="M7.64584 15.7108C7.23279 14.8966 7 13.9755 7 13C7 9.78484 9.5 7.5 13 7C17.0817 6.4169 18.8333 4.16667 20 3C23.5 16 17 19 13 19C11.9071 19 10.8825 18.7078 10 18.1973"
                        stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3 21C3.5 18 5.45791 16.1355 10 15C13.2167 14.1958 15.4634 12.1791 17 10.0549"
                        stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </div>
            <h1 class="text-2xl text-gray-800 mb-2">QUÊN MẬT KHẨU</h1>

        </div>

        {{-- Hiển thị lỗi chung --}}
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <form action="{{ route('forgot_password.send') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-gray-700">Nhập email để khôi phục mật khẩu: <span
                        class="text-red-500">*</span></label>
                <input id="email" name="email" type="email" placeholder="admin@school.edu" value="{{ old('email') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Gửi mã
            </button>
        </form>

        @session('status')
        <p class="mt-3 text-sm text-gray-800 italic">{{ $value }}</p>
        @endsession

        <form action="{{ route('forgot_password.verify') }}" method="POST" class="space-y-4 mt-3">
            @csrf
            <input type="hidden" name="email" value="{{ old('email') }}">
            <label for="email" class="text-sm font-medium text-gray-700">Mã xác thực: <span
                    class="text-red-500">*</span></label>
            <input type="text" name="code" inputmode="numeric" maxlength="6" placeholder="123456" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            @error('code')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Xác thực
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 text-sm text-green-600 hover:underline">
                {{-- SVG Icon ArrowLeft --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Quay về trang Đăng nhập
            </a>
        </div>
    </div>
</div>

@endsection