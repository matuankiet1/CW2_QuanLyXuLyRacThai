@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50 p-4">
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border border-gray-400">
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
            <h1 class="text-2xl text-gray-800 mb-2">KHÔI PHỤC MẬT KHẨU</h1>

        </div>

        {{-- Hiển thị lỗi chung --}}
        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <form action="{{ route('reset_password') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-2">
                <label for="passwordInput" class="text-sm font-medium text-gray-700">Nhập mật khẩu mới: <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" placeholder="••••••••" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <button type="button" id="togglePasswordInput"
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
            </div>

            <div class="space-y-2">
                <label for="rePasswordInput" class="text-sm font-medium text-gray-700">Xác nhận mật khẩu mới: <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" id="rePasswordInput" name="password_confirmation" placeholder="••••••••" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
                    @error('re-password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <button type="button" id="toggleRePasswordInput"
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
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Đổi mật khẩu
            </button>
        </form>
    </div>
</div>

<script>
    const passwordInput = document.getElementById("passwordInput");
    const togglePasswordInput = document.getElementById("togglePasswordInput");
    const rePasswordInput = document.getElementById("rePasswordInput");
    const toggleRePasswordInput = document.getElementById("toggleRePasswordInput");

    
    togglePasswordInput.addEventListener("click", () => {
        const isHidden = passwordInput.type === "password";
        passwordInput.type = isHidden ? "text" : "password";
        updateIcon(passwordInput.type, togglePasswordInput);
    });

    toggleRePasswordInput.addEventListener("click", () => {
        const isHidden = rePasswordInput.type === "password";
        rePasswordInput.type = isHidden ? "text" : "password";
        updateIcon(rePasswordInput.type, toggleRePasswordInput);
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