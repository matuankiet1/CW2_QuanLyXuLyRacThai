{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 to-emerald-50 p-4 relative overflow-hidden">
    {{-- Decorative image --}}
    <div class="absolute top-8 right-8 w-32 h-32 opacity-20 hidden md:block">
        <img src="https://images.unsplash.com/photo-1736977591945-6a62f3d621ca?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxyZWN5Y2xpbmclMjBlbnZpcm9ubWVudCUyMGdyZWVufGVufDF8fHx8MTc1OTk3MDUxNXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral"
             alt="Recycling"
             class="w-full h-full object-cover rounded-2xl" />
    </div>

    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg border border-gray-400 relative z-10">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-xl mb-4">
                {{-- SVG Icon Leaf --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 4 13H2a10 10 0 0 0 10 10z" />
                    <path d="M12 2a7 7 0 0 1 7 7h2a10 10 0 0 0-10-10z" />
                    <path d="M12 22a10 10 0 0 0 10-10h-2a7 7 0 0 1-7 7z" />
                    <path d="M2 12a10 10 0 0 0 10 10v-2a7 7 0 0 1-7-7z" />
                </svg>
            </div>
            <h1 class="text-2xl text-gray-800 mb-2">Đăng ký tài khoản</h1>
            <p class="text-gray-500">Tham gia vào hệ thống EcoSchool</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Full Name --}}
            <div class="space-y-2">
                <label for="name" class="text-sm font-medium text-gray-700">Họ và tên</label>
                <input id="name" name="name" type="text" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror" />
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" placeholder="nguyen.van.a@school.edu" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror" />
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Phone - assuming it's a field in your users table --}}
            <div class="space-y-2">
                <label for="phone" class="text-sm font-medium text-gray-700">Số điện thoại</label>
                <input id="phone" name="phone" type="tel" placeholder="0912345678" value="{{ old('phone') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('phone') border-red-500 @enderror" />
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="space-y-2">
                <label for="password" class="text-sm font-medium text-gray-700">Mật khẩu</label>
                <input id="password" name="password" type="password" placeholder="••••••••" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror" />
                <div class="text-xs text-gray-500">
                    <p>Mật khẩu phải có ít nhất 8 ký tự, bao gồm:</p>
                    <ul class="list-disc list-inside ml-2">
                        <li>Ít nhất 8 ký tự</li>
                        <li>Chữ hoa và chữ thường</li>
                        <li>Ít nhất 1 chữ số</li>
                        <li>Ít nhất 1 ký tự đặc biệt</li>
                    </ul>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-2">
                <label for="password_confirmation" class="text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>

            <button type="submit" class="cursor-pointer w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Đăng ký
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-green-600 hover:underline">
                {{-- SVG Icon ArrowLeft --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                </svg>
                Đã có tài khoản? Quay lại Đăng nhập
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordRequirements = document.querySelector('.text-xs.text-gray-500');
    
    if (passwordInput && passwordRequirements) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const requirements = passwordRequirements.querySelectorAll('li');
            
            // Reset all requirements
            requirements.forEach(li => {
                li.classList.remove('text-green-600', 'text-red-500');
                li.classList.add('text-gray-500');
            });
            
            // Check each requirement
            if (password.length >= 8) {
                requirements[0].classList.remove('text-gray-500', 'text-red-500');
                requirements[0].classList.add('text-green-600');
            } else {
                requirements[0].classList.remove('text-gray-500', 'text-green-600');
                requirements[0].classList.add('text-red-500');
            }
            
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
                requirements[1].classList.remove('text-gray-500', 'text-red-500');
                requirements[1].classList.add('text-green-600');
            } else {
                requirements[1].classList.remove('text-gray-500', 'text-green-600');
                requirements[1].classList.add('text-red-500');
            }
            
            if (/\d/.test(password)) {
                requirements[2].classList.remove('text-gray-500', 'text-red-500');
                requirements[2].classList.add('text-green-600');
            } else {
                requirements[2].classList.remove('text-gray-500', 'text-green-600');
                requirements[2].classList.add('text-red-500');
            }
            
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                requirements[3].classList.remove('text-gray-500', 'text-red-500');
                requirements[3].classList.add('text-green-600');
            } else {
                requirements[3].classList.remove('text-gray-500', 'text-green-600');
                requirements[3].classList.add('text-red-500');
            }
        });
    }
});
</script>
@endsection