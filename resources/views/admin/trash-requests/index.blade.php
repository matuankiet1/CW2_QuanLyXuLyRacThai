{{-- 
    View: Danh sách tất cả yêu cầu thu gom rác (Admin)
    Route: GET /admin/trash-requests
    Controller: TrashRequestController@adminIndex
--}}
@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container mx-auto px-4">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded bg-blue-100 text-blue-600 flex items-center justify-center" style="width:48px;height:48px;">
                    <i class="fas fa-list text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Tổng yêu cầu</div>
                    <div class="text-2xl font-semibold mb-0">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded bg-orange-100 text-orange-600 flex items-center justify-center" style="width:48px;height:48px;">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Chờ duyệt</div>
                    <div class="text-2xl font-semibold mb-0">{{ $stats['waiting_admin'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md h-full">
            <div class="p-4 flex items-center gap-3">
                <div class="rounded bg-green-100 text-green-600 flex items-center justify-center" style="width:48px;height:48px;">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Đã duyệt</div>
                    <div class="text-2xl font-semibold mb-0">{{ $stats['approved'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter và Search --}}
    <div class="bg-white rounded-lg shadow-md mb-4">
        <div class="p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium mb-1">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Tìm kiếm...">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Trạng thái</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Tất cả</option>
                        <option value="pending" {{ ($status ?? 'all') === 'pending' ? 'selected' : '' }}>Đang chờ</option>
                        <option value="assigned" {{ ($status ?? 'all') === 'assigned' ? 'selected' : '' }}>Đã gán</option>
                        <option value="waiting_admin" {{ ($status ?? 'all') === 'waiting_admin' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="admin_approved" {{ ($status ?? 'all') === 'admin_approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="admin_rejected" {{ ($status ?? 'all') === 'admin_rejected' ? 'selected' : '' }}>Bị từ chối</option>
                    </select>
                </div>
                <div class="md:col-span-5 flex flex-col md:flex-row gap-2 justify-end">
                    <button type="submit" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Actions --}}
    <form id="bulkScheduleForm" method="GET" action="{{ route('admin.trash-requests.bulk-schedule.form') }}" class="mb-4">
        <div class="bg-white rounded-lg shadow-md p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="selectAll" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <label for="selectAll" class="text-sm font-medium text-gray-700">Chọn tất cả</label>
            </div>
            <button type="submit" id="bulkScheduleBtn" disabled
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                <i class="fas fa-calendar-alt mr-2"></i>Chia lịch hàng loạt
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse table-auto">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-3 text-left font-semibold" style="width:50px">
                            <input type="checkbox" id="selectAllHeader" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </th>
                        <th class="px-4 py-3 text-left font-semibold" style="width:80px">STT</th>
                        <th class="px-4 py-3 text-left font-semibold">Địa điểm</th>
                        <th class="px-4 py-3 text-left font-semibold">Loại rác</th>
                        <th class="px-4 py-3 text-left font-semibold">Người gửi</th>
                        <th class="px-4 py-3 text-left font-semibold">Nhân viên</th>
                        <th class="px-4 py-3 text-left font-semibold">Trạng thái</th>
                        <th class="px-4 py-3 text-left font-semibold">Ngày tạo</th>
                        <th class="px-4 py-3 text-right font-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trashRequests as $index => $request)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-center">
                                @if(in_array($request->status, ['pending', 'assigned', 'waiting_admin']))
                                    <input type="checkbox" name="request_ids[]" value="{{ $request->request_id }}" 
                                           class="request-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">{{ $trashRequests->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ $request->location }}</td>
                            <td class="px-4 py-3">{{ $request->type }}</td>
                            <td class="px-4 py-3">{{ $request->student->name }}</td>
                            <td class="px-4 py-3">
                                {{ $request->assignedStaff ? $request->assignedStaff->name : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500',
                                        'assigned' => 'bg-blue-500',
                                        'staff_done' => 'bg-purple-500',
                                        'waiting_admin' => 'bg-orange-500',
                                        'admin_approved' => 'bg-green-500',
                                        'admin_rejected' => 'bg-red-500',
                                    ];
                                    $statusTexts = [
                                        'pending' => 'Đang chờ',
                                        'assigned' => 'Đã gán',
                                        'staff_done' => 'Đã hoàn thành',
                                        'waiting_admin' => 'Chờ duyệt',
                                        'admin_approved' => 'Đã duyệt',
                                        'admin_rejected' => 'Bị từ chối',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold text-white {{ $statusColors[$request->status] ?? 'bg-gray-500' }}">
                                    {{ $statusTexts[$request->status] ?? $request->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.trash-requests.show', $request->request_id) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-eye mr-1"></i>Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                Không tìm thấy yêu cầu nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($trashRequests->hasPages())
            <div class="p-4 border-t">
                {{ $trashRequests->appends(request()->query())->links('pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Select All functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.request-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkButton();
    });

    document.getElementById('selectAllHeader')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.request-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        document.getElementById('selectAll').checked = this.checked;
        updateBulkButton();
    });

    // Update bulk button state
    function updateBulkButton() {
        const checked = document.querySelectorAll('.request-checkbox:checked');
        const btn = document.getElementById('bulkScheduleBtn');
        if (btn) {
            btn.disabled = checked.length === 0;
        }
    }

    // Individual checkbox change
    document.querySelectorAll('.request-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkButton);
    });

    // Form submission - collect checked IDs
    document.getElementById('bulkScheduleForm')?.addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('.request-checkbox:checked');
        if (checked.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một yêu cầu để chia lịch.');
            return false;
        }

        // Add hidden inputs for selected IDs
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'request_ids[]';
            input.value = cb.value;
            this.appendChild(input);
        });
    });
</script>
@endpush
@endsection

