@extends('layouts.admin-with-sidebar')

@section('title', 'Báo cáo người dùng - EcoWaste Admin')

@section('content')
    <!-- Header -->
    <div class="gradient-bg text-white py-6 mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-2">Báo cáo từ người dùng</h1>
                    <p class="mb-0 opacity-75">Xem và quản lý các báo cáo từ người dùng</p>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($unreadCount > 0)
                        <span class="badge bg-danger fs-6 mb-2">
                            <i class="fas fa-bell me-2"></i>{{ $unreadCount }} báo cáo chưa đọc
                        </span>
                    @endif
                    <a href="{{ route('dashboard.admin') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Filter và Bulk Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.user-reports') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Lọc theo trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chưa xử lý</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã xử lý</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Lọc theo loại</label>
                        <select name="type" class="form-select">
                            <option value="">Tất cả loại</option>
                            <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>Khiếu nại</option>
                            <option value="suggestion" {{ request('type') == 'suggestion' ? 'selected' : '' }}>Đề xuất</option>
                            <option value="bug" {{ request('type') == 'bug' ? 'selected' : '' }}>Lỗi hệ thống</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái đọc</label>
                        <select name="read_status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="unread" {{ request('read_status') == 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                            <option value="read" {{ request('read_status') == 'read' ? 'selected' : '' }}>Đã đọc</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i>Lọc
                        </button>
                        <a href="{{ route('admin.reports.user-reports') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Xóa bộ lọc
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-20 me-3">
                                <i class="fas fa-flag fs-4"></i>
                            </div>
                            <div>
                                <p class="card-text small mb-1">Tổng báo cáo</p>
                                <h3 class="card-title mb-0">{{ $reports->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-20 me-3">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                            <div>
                                <p class="card-text small mb-1">Chưa xử lý</p>
                                <h3 class="card-title mb-0">{{ $reports->where('status', 'pending')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-20 me-3">
                                <i class="fas fa-spinner fs-4"></i>
                            </div>
                            <div>
                                <p class="card-text small mb-1">Đang xử lý</p>
                                <h3 class="card-title mb-0">{{ $reports->where('status', 'processing')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white hover-scale">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 rounded-circle bg-white bg-opacity-20 me-3">
                                <i class="fas fa-check-circle fs-4"></i>
                            </div>
                            <div>
                                <p class="card-text small mb-1">Đã xử lý</p>
                                <h3 class="card-title mb-0">{{ $reports->where('status', 'resolved')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        @if($reports->count() > 0)
        <div class="card mb-4">
            <div class="card-body">
                <form id="bulkActionForm" method="POST" action="{{ route('admin.reports.user-reports') }}">
                    @csrf
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <div class="form-check">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                                <label for="selectAll" class="form-check-label">Chọn tất cả</label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <select name="action" class="form-select">
                                <option value="">Chọn hành động</option>
                                <option value="mark_read">Đánh dấu đã đọc</option>
                                <option value="mark_unread">Đánh dấu chưa đọc</option>
                                <option value="update_status">Cập nhật trạng thái</option>
                                <option value="delete">Xóa báo cáo</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="statusSelect" style="display: none;">
                            <select name="status" class="form-select">
                                <option value="pending">Chưa xử lý</option>
                                <option value="processing">Đang xử lý</option>
                                <option value="resolved">Đã xử lý</option>
                            </select>
                        </div>
                        
                        <div class="col-auto">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn thực hiện hành động này?')">
                                <i class="fas fa-play me-2"></i>Thực hiện
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Reports List -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Danh sách báo cáo ({{ $reports->count() }} báo cáo)
                </h5>
            </div>
            
            <div class="card-body">
                @forelse($reports as $report)
                    <div class="card mb-3 {{ !$report->isRead() ? 'border-primary' : '' }}">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check me-3">
                                        <input type="checkbox" name="report_ids[]" value="{{ $report->id }}" class="form-check-input report-checkbox">
                                    </div>
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <span class="fw-bold">{{ strtoupper(substr($report->user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $report->user->name }}</h5>
                                        <p class="text-muted small mb-1">
                                            <i class="fas fa-envelope me-1"></i>{{ $report->user->email }}
                                        </p>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-user me-1"></i>ID: {{ $report->user->user_id }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    @if(!$report->isRead())
                                        <span class="badge bg-danger">
                                            <i class="fas fa-circle me-1"></i>Mới
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Report Content -->
                            <div class="mb-3">
                                <h4 class="fw-bold mb-2">{{ $report->title }}</h4>
                                <div class="bg-light p-3 rounded border-start border-primary border-4">
                                    <p class="mb-0">{{ $report->content }}</p>
                                </div>
                            </div>

                            <!-- Tags and Status -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <!-- Type Badge -->
                                @if($report->type === 'complaint')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Khiếu nại
                                    </span>
                                @elseif($report->type === 'suggestion')
                                    <span class="badge bg-info">
                                        <i class="fas fa-lightbulb me-1"></i>Đề xuất
                                    </span>
                                @elseif($report->type === 'bug')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-bug me-1"></i>Lỗi hệ thống
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-question-circle me-1"></i>Khác
                                    </span>
                                @endif

                                <!-- Status Badge -->
                                @if($report->status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i>Chưa xử lý
                                    </span>
                                @elseif($report->status === 'processing')
                                    <span class="badge bg-info">
                                        <i class="fas fa-spinner me-1"></i>Đang xử lý
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Đã xử lý
                                    </span>
                                @endif

                                <!-- Read Status -->
                                @if($report->isRead())
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Đã đọc
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="fas fa-exclamation-circle me-1"></i>Chưa đọc
                                    </span>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div class="text-muted small">
                                    <i class="fas fa-calendar me-1"></i>
                                    <span class="fw-medium">{{ $report->created_at->format('d/m/Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock me-1"></i>
                                    <span>{{ $report->created_at->format('H:i:s') }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="text-muted">{{ $report->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.reports.user-reports.show', $report->id) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                                    </a>
                                    
                                    <!-- Status Update Dropdown -->
                                    <select onchange="updateStatus({{ $report->id }}, this.value)" 
                                            class="form-select form-select-sm" style="width: auto;">
                                        <option value="">Cập nhật trạng thái</option>
                                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Chưa xử lý</option>
                                        <option value="processing" {{ $report->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Đã xử lý</option>
                                    </select>
                                    
                                    @if(!$report->isRead())
                                        <button onclick="markAsRead({{ $report->id }})" 
                                                class="btn btn-success btn-sm">
                                            <i class="fas fa-check me-1"></i>Đánh dấu đã đọc
                                        </button>
                                    @else
                                        <button onclick="markAsUnread({{ $report->id }})" 
                                                class="btn btn-warning btn-sm">
                                            <i class="fas fa-undo me-1"></i>Đánh dấu chưa đọc
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-2">Không có báo cáo nào</h4>
                        <p class="text-muted">Chưa có báo cáo nào từ người dùng.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.report-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Show/hide status select based on action
        document.querySelector('select[name="action"]').addEventListener('change', function() {
            const statusSelect = document.getElementById('statusSelect');
            if (this.value === 'update_status') {
                statusSelect.style.display = 'block';
            } else {
                statusSelect.style.display = 'none';
            }
        });

        // Mark as read
        function markAsRead(reportId) {
            if (confirm('Bạn có chắc muốn đánh dấu báo cáo này là đã đọc?')) {
                fetch(`/reports/user-reports/${reportId}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            if (status && confirm(`Bạn có chắc muốn cập nhật trạng thái báo cáo thành "${status}"?`)) {
                fetch(`/reports/user-reports/${reportId}/status-ajax`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

@push('styles')
<style>
    .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card-shadow { box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .hover-scale { transition: transform 0.2s ease-in-out; }
    .hover-scale:hover { transform: scale(1.02); }
</style>
@endpush
