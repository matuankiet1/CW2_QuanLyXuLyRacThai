@extends('layouts.user')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-lg mt-10">
    <h2 class="text-2xl font-bold mb-6">Gửi phản hồi</h2>

    <form method="POST" action="{{ route('user.feedback.store') }}">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Họ tên</label>
            <input type="text" value="{{ auth()->user()->name }}" 
                   disabled
                   class="w-full mt-1 p-3 border rounded-lg bg-gray-100">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Email</label>
            <input type="email" value="{{ auth()->user()->email }}" 
                   disabled
                   class="w-full mt-1 p-3 border rounded-lg bg-gray-100">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Tiêu đề <span class="text-red-500">*</span></label>
            <input type="text" name="subject"
                   value="{{ old('subject') }}"
                   class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Nhập tiêu đề phản hồi" required>
            @error('subject')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-6">
            <label class="font-semibold">Nội dung <span class="text-red-500">*</span></label>
            <textarea name="message"
                      rows="6"
                      class="w-full mt-1 p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Mô tả chi tiết phản hồi của bạn..." required>{{ old('message') }}</textarea>
            @error('message')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-between items-center">
            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                <i class="fas fa-paper-plane mr-2"></i>
                Gửi phản hồi
            </button>
        </div>
    </form>
</div>

{{-- Nút xem feedback ở góc dưới phải --}}
<div class="fixed bottom-6 right-6">
    <a href="{{ route('user.feedback.index') }}" 
       class="bg-blue-600 text-white px-5 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition flex items-center space-x-2 group">
        <i class="fas fa-comments"></i>
        <span class="font-medium">Xem phản hồi của tôi</span>
        
        {{-- Badge thông báo nếu có reply --}}
        @auth
            @php
                $repliedCount = \App\Models\Feedback::where('user_id', auth()->id())
                    ->whereNotNull('reply')
                    ->count();
            @endphp
            
            @if($repliedCount > 0)
            <span class="bg-green-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center animate-pulse">
                {{ $repliedCount }}
            </span>
            @endif
        @endauth
    </a>
</div>
@endsection