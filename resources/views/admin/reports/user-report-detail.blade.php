@extends('layouts.dashboard')

@section('main-content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.reports.user-reports') }}" class="text-green-600 hover:text-green-800">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
        </a>
        @if(!$report->isRead())
            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800">
                <i class="fas fa-circle mr-2 text-xs"></i>Chưa đọc
            </span>
        @endif
    </div>

    <!-- Report Detail -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $report->title }}</h2>
                        @if($report->type === 'complaint')
                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-circle mr-1"></i>Khiếu nại
                            </span>
                        @elseif($report->type === 'suggestion')
                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-lightbulb mr-1"></i>Đề xuất
                            </span>
                        @elseif($report->type === 'bug')
                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                                <i class="fas fa-bug mr-1"></i>Lỗi hệ thống
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                <i class="fas fa-info-circle mr-1"></i>Khác
                            </span>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $report->content }}</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>
                            <i class="fas fa-clock mr-2"></i>
                            Gửi vào {{ $report->created_at->format('d/m/Y H:i') }}
                        </span>
                        @if($report->read_at)
                            <span>
                                <i class="fas fa-eye mr-2"></i>
                                Đã đọc {{ $report->read_at->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Người gửi</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-lg font-medium text-green-700">
                                    {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $report->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $report->user->email }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Update -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Cập nhật trạng thái</h3>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('admin.reports.user-reports.update-status', $report->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Trạng thái
                            </label>
                            <select name="status" id="status" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>
                                    Chưa xử lý
                                </option>
                                <option value="processing" {{ $report->status === 'processing' ? 'selected' : '' }}>
                                    Đang xử lý
                                </option>
                                <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>
                                    Đã xử lý
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            <i class="fas fa-save mr-2"></i>Cập nhật
                        </button>
                    </form>
                </div>
            </div>

            <!-- Report Info -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Thông tin</h3>
                </div>
                <div class="px-6 py-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID báo cáo:</span>
                        <span class="font-medium text-gray-900">#{{ $report->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày gửi:</span>
                        <span class="font-medium text-gray-900">{{ $report->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Giờ gửi:</span>
                        <span class="font-medium text-gray-900">{{ $report->created_at->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

