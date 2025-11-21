@extends('layouts.admin-with-sidebar')

@section('title', 'Báo cáo người dùng - EcoWaste Admin')

@section('content')
<div class="space-y-6 p-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">Báo cáo từ người dùng</h1>
                <p class="text-indigo-100">Xem và quản lý các báo cáo từ người dùng</p>
            </div>
            <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                @if($unreadCount > 0)
                    <span class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg font-semibold shadow-md">
                        <i class="fas fa-bell mr-2"></i>{{ $unreadCount }} báo cáo chưa đọc
                    </span>
                @endif
                <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white rounded-lg transition-colors font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.user-reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lọc theo trạng thái</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã xử lý</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lọc theo loại</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả loại</option>
                    <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>Khiếu nại</option>
                    <option value="suggestion" {{ request('type') == 'suggestion' ? 'selected' : '' }}>Đề xuất</option>
                    <option value="bug" {{ request('type') == 'bug' ? 'selected' : '' }}>Lỗi hệ thống</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái đọc</label>
                <select name="read_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả</option>
                    <option value="unread" {{ request('read_status') == 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                    <option value="read" {{ request('read_status') == 'read' ? 'selected' : '' }}>Đã đọc</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    <i class="fas fa-search mr-2"></i>Lọc
                </button>
                <a href="{{ route('admin.reports.user-reports') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>Xóa
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                    <i class="fas fa-flag text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium opacity-90 mb-1">Tổng báo cáo</p>
                    <h3 class="text-3xl font-bold">{{ $reports->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium opacity-90 mb-1">Chưa xử lý</p>
                    <h3 class="text-3xl font-bold">{{ $reports->where('status', 'pending')->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                    <i class="fas fa-spinner text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium opacity-90 mb-1">Đang xử lý</p>
                    <h3 class="text-3xl font-bold">{{ $reports->where('status', 'processing')->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium opacity-90 mb-1">Đã xử lý</p>
                    <h3 class="text-3xl font-bold">{{ $reports->where('status', 'resolved')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    @if($reports->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form id="bulkActionForm" method="POST" action="{{ route('admin.reports.user-reports') }}">
            @csrf
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex items-center">
                    <input type="checkbox" id="selectAll" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">Chọn tất cả</label>
                </div>
                
                <div class="flex-1 md:max-w-xs">
                    <select name="action" id="bulkAction" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Chọn hành động</option>
                        <option value="mark_read">Đánh dấu đã đọc</option>
                        <option value="mark_unread">Đánh dấu chưa đọc</option>
                        <option value="update_status">Cập nhật trạng thái</option>
                        <option value="delete">Xóa báo cáo</option>
                    </select>
                </div>
                
                <div id="statusSelect" class="flex-1 md:max-w-xs hidden">
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="pending">Chưa xử lý</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="resolved">Đã xử lý</option>
                    </select>
                </div>
                
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium" onclick="return confirm('Bạn có chắc muốn thực hiện hành động này?')">
                    <i class="fas fa-play mr-2"></i>Thực hiện
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Reports List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-list mr-2"></i>Danh sách báo cáo ({{ $reports->count() }} báo cáo)
            </h3>
        </div>
        
        <div class="p-6">
            @forelse($reports as $report)
                <div class="border rounded-lg mb-4 {{ !$report->isRead() ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }} hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
                            <div class="flex items-start gap-4">
                                <div class="flex items-center pt-1">
                                    <input type="checkbox" name="report_ids[]" value="{{ $report->id }}" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 report-checkbox">
                                </div>
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white flex items-center justify-center shadow-lg shrink-0">
                                    <span class="text-lg font-bold">{{ strtoupper(substr($report->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h5 class="text-lg font-semibold text-gray-900">{{ $report->user->name }}</h5>
                                        @if(!$report->isRead())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                                                <i class="fas fa-circle mr-1 text-xs"></i>Mới
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        <i class="fas fa-envelope mr-1"></i>{{ $report->user->email }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-user mr-1"></i>ID: {{ $report->user->user_id }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Report Content -->
                        <div class="mb-4">
                            <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $report->title }}</h4>
                            <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-indigo-500">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $report->content }}</p>
                            </div>
                        </div>

                        <!-- Tags and Status -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <!-- Type Badge -->
                            @if($report->type === 'complaint')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Khiếu nại
                                </span>
                            @elseif($report->type === 'suggestion')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    <i class="fas fa-lightbulb mr-1"></i>Đề xuất
                                </span>
                            @elseif($report->type === 'bug')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                                    <i class="fas fa-bug mr-1"></i>Lỗi hệ thống
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                    <i class="fas fa-question-circle mr-1"></i>Khác
                                </span>
                            @endif

                            <!-- Status Badge -->
                            @if($report->status === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Chưa xử lý
                                </span>
                            @elseif($report->status === 'processing')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                    <i class="fas fa-spinner mr-1"></i>Đang xử lý
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Đã xử lý
                                </span>
                            @endif

                            <!-- Read Status -->
                            @if($report->isRead())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Đã đọc
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Chưa đọc
                                </span>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between pt-4 border-t border-gray-200 gap-4">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-calendar mr-1"></i>
                                <span class="font-medium">{{ $report->created_at->format('d/m/Y') }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock mr-1"></i>
                                <span>{{ $report->created_at->format('H:i:s') }}</span>
                                <span class="mx-2">•</span>
                                <span class="text-gray-500">{{ $report->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.reports.user-reports.show', $report->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>Xem chi tiết
                                </a>
                                
                                <!-- Status Update Dropdown -->
                                <select data-report-id="{{ $report->id }}" 
                                        class="status-update-select px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm">
                                    <option value="">Cập nhật trạng thái</option>
                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Chưa xử lý</option>
                                    <option value="processing" {{ $report->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Đã xử lý</option>
                                </select>
                                
                                @if(!$report->isRead())
                                    <button type="button" data-report-id="{{ $report->id }}" 
                                            class="mark-read-btn inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                        <i class="fas fa-check mr-1"></i>Đánh dấu đã đọc
                                    </button>
                                @else
                                    <button type="button" data-report-id="{{ $report->id }}" 
                                            class="mark-unread-btn inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
                                        <i class="fas fa-undo mr-1"></i>Đánh dấu chưa đọc
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-400 mb-4 text-6xl"></i>
                    <h4 class="text-xl font-semibold text-gray-600 mb-2">Không có báo cáo nào</h4>
                    <p class="text-gray-500">Chưa có báo cáo nào từ người dùng.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Select All functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.report-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Show/hide status select based on action
    document.getElementById('bulkAction')?.addEventListener('change', function() {
        const statusSelect = document.getElementById('statusSelect');
        if (this.value === 'update_status') {
            statusSelect.classList.remove('hidden');
        } else {
            statusSelect.classList.add('hidden');
        }
    });

    // Status update dropdown handlers
    document.querySelectorAll('.status-update-select').forEach(select => {
        select.addEventListener('change', function() {
            const reportId = this.getAttribute('data-report-id');
            const status = this.value;
            if (status) {
                updateStatus(reportId, status);
            }
        });
    });

    // Mark as read button handlers
    document.querySelectorAll('.mark-read-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.getAttribute('data-report-id');
            markAsRead(reportId);
        });
    });

    // Mark as unread button handlers
    document.querySelectorAll('.mark-unread-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.getAttribute('data-report-id');
            markAsUnread(reportId);
        });
    });

    // Mark as read
    function markAsRead(reportId) {
        if (confirm('Bạn có chắc muốn đánh dấu báo cáo này là đã đọc?')) {
            fetch(`/reports/user-reports/${reportId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        }
    }

    // Mark as unread
    function markAsUnread(reportId) {
        if (confirm('Bạn có chắc muốn đánh dấu báo cáo này là chưa đọc?')) {
            fetch(`/reports/user-reports/${reportId}/mark-unread`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        }
    }

    // Update status
    function updateStatus(reportId, status) {
        const statusLabels = {
            'pending': 'Chưa xử lý',
            'processing': 'Đang xử lý',
            'resolved': 'Đã xử lý'
        };
        
        if (status && confirm(`Bạn có chắc muốn cập nhật trạng thái báo cáo thành "${statusLabels[status]}"?`)) {
            fetch(`/reports/user-reports/${reportId}/status-ajax`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái');
            });
        }
    }
</script>
@endsection
