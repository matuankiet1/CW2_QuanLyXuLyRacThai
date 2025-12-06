@extends('layouts.user')

@section('title', 'Phản Hồi Của Tôi')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md mt-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Phản Hồi Của Tôi</h2>
        <a href="{{ route('user.feedback.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Gửi Phản Hồi Mới
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-50">
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Tiêu Đề</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Ngày Gửi</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Trạng Thái</th>
                    <th class="py-3 px-4 text-left text-sm font-semibold text-gray-600">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedback as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <div class="font-medium text-gray-800">{{ $item->subject }}</div>
                        <div class="text-sm text-gray-600 truncate max-w-xs">{{ Str::limit($item->message, 50) }}</div>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-600">
                        {{ $item->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="py-3 px-4">
                        @if($item->isReplied())
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                <i class="fas fa-check mr-1"></i> Đã phản hồi
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                <i class="fas fa-clock mr-1"></i> Đang chờ
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('user.feedback.show', $item->id) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            <i class="fas fa-eye mr-1"></i> Xem chi tiết
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <div class="text-lg">Chưa có phản hồi nào</div>
                        <a href="{{ route('user.feedback.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            Gửi phản hồi đầu tiên của bạn
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($feedback->hasPages())
    <div class="mt-6">
        {{ $feedback->links() }}
    </div>
    @endif
</div>
@endsection