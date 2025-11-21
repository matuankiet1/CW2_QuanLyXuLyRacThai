{{-- 
    View: Quản lý sinh viên tham gia sự kiện (Admin)
    Route: GET /admin/events/{id}/participants
    Controller: EventParticipantController@index
    
    Chức năng:
    - Hiển thị danh sách sinh viên tham gia sự kiện
    - Tìm kiếm và lọc theo trạng thái
    - Xác nhận sinh viên
    - Điểm danh sinh viên
    - Xác nhận/Điểm danh hàng loạt
    - Xuất báo cáo
--}}
@extends('layouts.admin')

@section('title', 'Quản lý sinh viên tham gia - ' . $event->title)

@section('page-header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý sinh viên tham gia</h1>
        <p class="text-gray-600 mt-1">{{ $event->title }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
        <a href="{{ route('admin.events.rewards.index', $event->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
            <i class="fas fa-trophy mr-2"></i>Điểm thưởng
        </a>
        <a href="{{ route('admin.events.participants.export', $event->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-file-excel mr-2"></i>Xuất Excel
        </a>
    </div>
</div>
@endsection

@section('breadcrumb')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm text-gray-600">
        <li><a href="{{ route('admin.home') }}" class="hover:text-green-600">Trang chủ</a></li>
        <li><i class="fas fa-chevron-right text-xs"></i></li>
        <li><a href="{{ route('admin.events.index') }}" class="hover:text-green-600">Sự kiện</a></li>
        <li><i class="fas fa-chevron-right text-xs"></i></li>
        <li class="text-gray-900 font-medium">Quản lý sinh viên</li>
    </ol>
</nav>
@endsection

@section('content')
{{-- Event Info Card --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Tổng đăng ký</div>
        </div>
        <div class="text-center p-4 bg-yellow-50 rounded-lg">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Chờ xác nhận</div>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <div class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Đã xác nhận</div>
        </div>
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['attended'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Đã tham gia</div>
        </div>
    </div>
</div>

{{-- Filter và Search --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.events.participants', $event->id) }}" class="flex flex-col md:flex-row gap-4">
        {{-- Tìm kiếm --}}
        <div class="flex-1">
            <input type="text" 
                   name="search" 
                   value="{{ $search ?? '' }}" 
                   placeholder="Tìm kiếm theo tên hoặc email..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
        </div>
        
        {{-- Lọc theo trạng thái --}}
        <div class="md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
                <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="confirmed" {{ ($status ?? '') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                <option value="attended" {{ ($status ?? '') === 'attended' ? 'selected' : '' }}>Đã tham gia</option>
                <option value="canceled" {{ ($status ?? '') === 'canceled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>
        
        {{-- Nút tìm kiếm --}}
        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-search mr-2"></i>Tìm kiếm
        </button>
    </form>
</div>

{{-- Bulk Actions --}}
@if($participants->count() > 0)
<div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form id="bulkForm" method="POST" class="flex items-center gap-4">
        @csrf
        <div class="flex items-center gap-2">
            <input type="checkbox" id="selectAll" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
            <label for="selectAll" class="text-sm font-medium text-gray-700">Chọn tất cả</label>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="bulkAction('confirm')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                <i class="fas fa-check mr-1"></i>Xác nhận hàng loạt
            </button>
            <button type="button" onclick="bulkAction('attend')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                <i class="fas fa-user-check mr-1"></i>Điểm danh hàng loạt
            </button>
        </div>
    </form>
</div>
@endif

{{-- Participants Table --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-12">
                        <input type="checkbox" id="selectAllTable" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">STT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Họ và tên</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ngày đăng ký</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ngày xác nhận</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ngày điểm danh</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($participants as $index => $participant)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="user_ids[]" value="{{ $participant->user_id }}" class="participant-checkbox w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ $participants->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                            {{ $participant->user->name }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $participant->user->email }}
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'attended' => 'bg-green-100 text-green-800',
                                    'canceled' => 'bg-red-100 text-red-800',
                                ];
                                $statusTexts = [
                                    'pending' => 'Chờ xác nhận',
                                    'confirmed' => 'Đã xác nhận',
                                    'attended' => 'Đã tham gia',
                                    'canceled' => 'Đã hủy',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$participant->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusTexts[$participant->status] ?? $participant->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $participant->registered_at ? $participant->registered_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $participant->confirmed_at ? $participant->confirmed_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $participant->attended_at ? $participant->attended_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right text-sm">
                            <div class="flex justify-end gap-2">
                                @if($participant->status === 'pending')
                                    <form action="{{ route('admin.events.participants.confirm', [$event->id, $participant->user_id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-xs">
                                            <i class="fas fa-check mr-1"></i>Xác nhận
                                        </button>
                                    </form>
                                @endif
                                
                                @if($participant->status === 'confirmed')
                                    <form action="{{ route('admin.events.participants.attend', [$event->id, $participant->user_id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-xs">
                                            <i class="fas fa-user-check mr-1"></i>Điểm danh
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                            <p>Không có sinh viên nào tham gia sự kiện này.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    @if($participants->hasPages())
        <div class="border-t px-4 py-4">
            {{ $participants->appends(request()->query())->links('pagination.tailwind') }}
        </div>
    @endif
</div>

{{-- Bulk Action Forms (Hidden) --}}
<form id="bulkConfirmForm" action="{{ route('admin.events.participants.bulk-confirm', $event->id) }}" method="POST" style="display: none;">
    @csrf
</form>

<form id="bulkAttendForm" action="{{ route('admin.events.participants.bulk-attend', $event->id) }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('scripts')
<script>
    // Select All functionality
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.participant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        document.getElementById('selectAllTable').checked = this.checked;
    });

    document.getElementById('selectAllTable')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.participant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        document.getElementById('selectAll').checked = this.checked;
    });

    // Update select all when individual checkbox changes
    document.querySelectorAll('.participant-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(document.querySelectorAll('.participant-checkbox')).every(cb => cb.checked);
            document.getElementById('selectAll').checked = allChecked;
            document.getElementById('selectAllTable').checked = allChecked;
        });
    });

    // Bulk Action
    function bulkAction(action) {
        const selected = Array.from(document.querySelectorAll('.participant-checkbox:checked')).map(cb => cb.value);
        
        if (selected.length === 0) {
            alert('Vui lòng chọn ít nhất một sinh viên.');
            return;
        }

        if (!confirm(`Bạn có chắc chắn muốn ${action === 'confirm' ? 'xác nhận' : 'điểm danh'} ${selected.length} sinh viên?`)) {
            return;
        }

        const form = action === 'confirm' ? document.getElementById('bulkConfirmForm') : document.getElementById('bulkAttendForm');
        
        // Add hidden inputs for each selected user_id
        selected.forEach(userId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = userId;
            form.appendChild(input);
        });

        form.submit();
    }
</script>
@endpush

