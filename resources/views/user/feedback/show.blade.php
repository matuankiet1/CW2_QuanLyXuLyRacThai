@extends('layouts.user')

@section('title', 'Chi Tiết Phản Hồi')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md mt-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Chi Tiết Phản Hồi</h2>
        <a href="{{ route('user.feedback.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <!-- Thông tin phản hồi -->
    <div class="bg-gray-50 border rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-lg mb-4 text-gray-700">Thông Tin Phản Hồi Của Bạn</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="font-semibold text-gray-600">Tiêu đề:</label>
                <p class="mt-1 text-gray-800">{{ $feedback->subject }}</p>
            </div>
            <div>
                <label class="font-semibold text-gray-600">Ngày gửi:</label>
                <p class="mt-1 text-gray-800">{{ $feedback->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="mb-4">
            <label class="font-semibold text-gray-600">Nội dung:</label>
            <div class="mt-2 bg-white border rounded p-4 text-gray-700">
                {{ $feedback->message }}
            </div>
        </div>

        <div>
            <label class="font-semibold text-gray-600">Trạng thái:</label>
            <p class="mt-1">
                @if($feedback->isReplied())
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                        <i class="fas fa-check mr-1"></i> Đã được phản hồi
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                        <i class="fas fa-clock mr-1"></i> Đang chờ phản hồi
                    </span>
                @endif
            </p>
        </div>
    </div>

    <!-- Phản hồi từ admin -->
    @if($feedback->isReplied())
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <h3 class="font-semibold text-lg mb-4 text-green-800">
            <i class="fas fa-reply mr-2"></i>Phản Hồi Từ Quản Trị Viên
        </h3>
        
        <div class="bg-white rounded-lg p-4 mb-3">
            <div class="text-gray-700 leading-relaxed">
                {{ $feedback->reply }}
            </div>
        </div>
        
        <div class="text-sm text-green-600">
            <i class="fas fa-clock mr-1"></i>
            Phản hồi vào: {{ $feedback->reply_at->format('d/m/Y H:i') }}
        </div>
    </div>
    @else
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
        <i class="fas fa-clock text-4xl text-blue-400 mb-3"></i>
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Đang chờ phản hồi</h3>
        <p class="text-blue-600">Phản hồi của bạn đã được gửi thành công. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
    </div>
    @endif

    <!-- Nút hành động -->
    <div class="mt-6 flex justify-between">
        <a href="{{ route('user.feedback.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
            <i class="fas fa-list mr-2"></i> Danh sách phản hồi
        </a>
        
        @if(!$feedback->isReplied())
        <a href="{{ route('user.feedback.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Gửi phản hồi mới
        </a>
        @endif
    </div>
</div>
@endsection