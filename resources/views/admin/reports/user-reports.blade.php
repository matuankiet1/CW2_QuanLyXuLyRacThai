@extends('layouts.admin-with-sidebar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Báo cáo từ người dùng</h2>
            <p class="text-gray-600 mt-1">Xem và quản lý các báo cáo từ người dùng</p>
        </div>
        @if($unreadCount > 0)
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded-lg font-semibold">
                <i class="fas fa-bell me-2"></i>{{ $unreadCount }} báo cáo chưa đọc
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20">
                    <i class="fas fa-flag text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Tổng báo cáo</p>
                    <p class="text-3xl font-bold">{{ $reports->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Chưa xử lý</p>
                    <p class="text-3xl font-bold">{{ $reports->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20">
                    <i class="fas fa-spinner text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Đang xử lý</p>
                    <p class="text-3xl font-bold">{{ $reports->where('status', 'processing')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium opacity-90">Đã xử lý</p>
                    <p class="text-3xl font-bold">{{ $reports->where('status', 'resolved')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Danh sách báo cáo</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Người gửi
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-file-text mr-2"></i>Nội dung báo cáo
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-tag mr-2"></i>Loại báo cáo
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>Trạng thái
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Thời gian
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Hành động
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 {{ !$report->isRead() ? 'bg-blue-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center shadow-lg">
                                        <span class="text-lg font-bold text-white">
                                            {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm font-semibold text-gray-900">{{ $report->user->name }}</div>
                                        @if(!$report->isRead())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                                <i class="fas fa-circle mr-1"></i>
                                                Mới
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-envelope mr-1"></i>
                                        {{ $report->user->email }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        ID: {{ $report->user->user_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 mb-2">{{ $report->title }}</div>
                            <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg max-w-md">
                                <div class="max-h-20 overflow-y-auto">
                                    {{ $report->content }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @if($report->type === 'complaint')
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Khiếu nại
                                    </span>
                                @elseif($report->type === 'suggestion')
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-lightbulb mr-1"></i>
                                        Đề xuất
                                    </span>
                                @elseif($report->type === 'bug')
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-orange-100 text-orange-800">
                                        <i class="fas fa-bug mr-1"></i>
                                        Lỗi hệ thống
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-question-circle mr-1"></i>
                                        Khác
                                    </span>
                                @endif
                                <div class="text-xs text-gray-500">
                                    {{ ucfirst($report->type) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                @if($report->status === 'pending')
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Chưa xử lý
                                    </span>
                                @elseif($report->status === 'processing')
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-spinner mr-1"></i>
                                        Đang xử lý
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Đã xử lý
                                    </span>
                                @endif
                                <div class="text-xs text-gray-500">
                                    {{ ucfirst($report->status) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">
                                {{ $report->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $report->created_at->format('H:i:s') }}
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ $report->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.reports.user-reports.show', $report->id) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                    <i class="fas fa-eye mr-1"></i>
                                    Xem chi tiết
                                </a>
                                @if(!$report->isRead())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Chưa đọc
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Đã đọc
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Không có báo cáo nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

