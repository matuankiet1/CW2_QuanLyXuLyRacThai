@extends('layouts.admin-with-sidebar')

@section('title', 'Chi Tiết Phản Hồi #' . $feedback->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Chi Tiết Phản Hồi
            </h1>
            <p class="text-gray-600 mt-1">ID: #{{ $feedback->id }}</p>
        </div>
        <a href="{{ route('admin.feedback.index') }}" 
           class="px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <!-- Alert -->
    @if(session('success'))
    <div class="bg-green-500 text-white p-4 rounded-xl animate-slide-down shadow-lg">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Feedback Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Feedback Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Thông Tin Phản Hồi
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Người gửi</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $feedback->user->name ?? 'Ẩn danh' }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-1 text-xs rounded-full {{ $feedback->user && $feedback->user->is_admin ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $feedback->user && $feedback->user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
                        <p class="text-lg text-gray-800">{{ $feedback->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Ngày gửi</label>
                        <p class="text-gray-800">{{ $feedback->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Trạng thái</label>
                        @if($feedback->isReplied())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i> Đã trả lời
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                            <i class="fas fa-clock mr-1"></i> Chờ phản hồi
                        </span>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Tiêu đề</label>
                    <p class="text-xl font-bold text-gray-800 bg-blue-50 p-4 rounded-xl border border-blue-200">
                        {{ $feedback->subject }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Nội dung</label>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-700 leading-relaxed">
                        {{ $feedback->message }}
                    </div>
                </div>
            </div>

            <!-- Admin Reply Section -->
            @if($feedback->reply)
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-reply"></i>
                    Phản Hồi Của Admin
                </h2>
                
                <div class="bg-white/20 backdrop-blur rounded-xl p-4 mb-3 border border-white/30">
                    <div class="text-white leading-relaxed">
                        {{ $feedback->reply }}
                    </div>
                </div>
                
                <div class="text-green-100 text-sm flex items-center gap-2">
                    <i class="fas fa-clock"></i>
                    Phản hồi lúc: {{ $feedback->reply_at->format('d/m/Y H:i') }}
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Reply Form -->
        <div class="space-y-6">
            <!-- Reply Form -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-edit text-green-500"></i>
                    Phản Hồi Cho Người Dùng
                </h2>

                <form action="{{ route('admin.feedback.reply', $feedback->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">
                            Nội dung phản hồi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reply" 
                                  rows="8"
                                  class="w-full p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                  placeholder="Nhập nội dung phản hồi cho người dùng..."
                                  required>{{ old('reply', $feedback->reply) }}</textarea>
                        @error('reply')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 px-6 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl font-semibold">
                        <i class="fas fa-paper-plane mr-2"></i>
                        {{ $feedback->isReplied() ? 'Cập Nhật Phản Hồi' : 'Gửi Phản Hồi' }}
                    </button>
                </form>
            </div>

            <!-- Quick Stats -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                <h3 class="font-semibold mb-4">Thông tin nhanh</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span>ID Phản hồi:</span>
                        <span class="font-bold">#{{ $feedback->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Đã trả lời:</span>
                        <span class="font-bold">{{ $feedback->isReplied() ? 'Có' : 'Chưa' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Thời gian chờ:</span>
                        <span class="font-bold">{{ $feedback->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.animate-slide-down {
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection