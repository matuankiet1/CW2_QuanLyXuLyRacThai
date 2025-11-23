@extends('layouts.admin-with-sidebar')

@section('title', 'Quản Lý Phản Hồi')

@section('content')
<div class="card bg-white rounded-2xl shadow-xl border border-white/20 backdrop-blur-sm">
    <!-- Header -->
    <div class="card-header bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-t-2xl p-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                    Quản Lý Phản Hồi
                </h2>
                <p class="text-blue-100 mt-2">Theo dõi và phản hồi ý kiến từ người dùng</p>
            </div>
            <div class="bg-white/20 px-4 py-2 rounded-xl backdrop-blur border border-white/30">
                <div class="flex items-center gap-2 text-white">
                    <i class="fas fa-comments"></i>
                    <span class="font-semibold">Tổng: {{ $feedback->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-6">
        <!-- Alert -->
        @if(session('success'))
        <div class="alert bg-green-500 text-white p-4 rounded-xl mb-6 animate-slide-down">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <!-- Table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-blue-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Người Gửi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nội Dung</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thời Gian</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Trạng Thái</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($feedback as $item)
                    <tr class="hover:bg-gradient-to-r from-blue-50/50 to-purple-50/50 transition-all duration-300 group">
                        <!-- User Column -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $item->user->name ?? 'Ẩn danh' }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->user->email ?? 'N/A' }}</div>
                                    <div class="text-xs mt-1">
                                        <span class="px-2 py-1 rounded-full {{ $item->user && $item->user->is_admin ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $item->user && $item->user->is_admin ? 'Admin' : 'User' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Content Column -->
                        <td class="px-6 py-4">
                            <div class="max-w-md">
                                <div class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                    {{ $item->subject }}
                                </div>
                                <div class="text-sm text-gray-600 mt-1 line-clamp-2">
                                    {{ $item->message }}
                                </div>
                            </div>
                        </td>

                        <!-- Time Column -->
                        <td class="px-6 py-4">
                            <div class="text-center">
                                <div class="font-semibold text-gray-900">{{ $item->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $item->created_at->format('H:i') }}</div>
                            </div>
                        </td>

                        <!-- Status Column -->
                        <td class="px-6 py-4">
                            @if($item->isReplied())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Đã trả lời
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                                <i class="fas fa-clock mr-1"></i> Chờ phản hồi
                            </span>
                            @endif
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.feedback.show', $item->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <i class="fas fa-eye mr-2"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-3"></i>
                                <div class="text-lg font-semibold">Chưa có phản hồi nào</div>
                                <p class="text-sm mt-1">Sẽ hiển thị ở đây khi có phản hồi mới</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($feedback->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $feedback->links() }}
        </div>
        @endif
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

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection