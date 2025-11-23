{{-- 
    View: Danh sách sinh viên nhận điểm thưởng khi tham gia sự kiện (Admin)
    Route: GET /admin/events/{id}/rewards
    Controller: EventRewardController@index
    
    Chức năng:
    - Hiển thị danh sách sinh viên đã tham gia và nhận điểm thưởng
    - Tìm kiếm sinh viên
    - Cập nhật điểm thưởng cho từng sinh viên
    - Cập nhật điểm thưởng hàng loạt
    - Sắp xếp theo điểm thưởng, ngày điểm danh, tên
--}}
@extends('layouts.admin-with-sidebar')

@section('title', 'Danh sách sinh viên nhận điểm thưởng - ' . $event->title)

@section('page-header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Danh sách sinh viên nhận điểm thưởng</h1>
        <p class="text-gray-600 mt-1">{{ $event->title }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.events.participants', $event->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
        <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <i class="fas fa-list mr-2"></i>Danh sách sự kiện
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
        <li class="text-gray-900 font-medium">Điểm thưởng</li>
    </ol>
</nav>
@endsection

@section('content')
{{-- Event Info Card --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Tổng sinh viên đã tham gia</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                </div>
                <i class="fas fa-users text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Sinh viên có điểm thưởng</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['with_rewards'] }}</p>
                </div>
                <i class="fas fa-star text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Tổng điểm thưởng</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($stats['total_points']) }}</p>
                </div>
                <i class="fas fa-trophy text-4xl opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Search and Filter --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('admin.events.rewards.index', $event->id) }}" class="flex items-center gap-4">
        <div class="flex-1">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Tìm kiếm theo tên hoặc email..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <i class="fas fa-search mr-2"></i>Tìm kiếm
        </button>
        @if($search ?? false)
            <a href="{{ route('admin.events.rewards.index', $event->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                <i class="fas fa-times mr-2"></i>Xóa
            </a>
        @endif
    </form>
</div>

{{-- Bulk Update Reward Points --}}
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-edit mr-2 text-yellow-600"></i>Cập nhật điểm thưởng hàng loạt
    </h3>
    <form method="POST" action="{{ route('admin.events.rewards.bulk-update', $event->id) }}" class="flex items-center gap-4">
        @csrf
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Điểm thưởng</label>
            <input 
                type="number" 
                name="reward_points" 
                min="0" 
                max="1000" 
                value="0" 
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
            >
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Chọn sinh viên (tích vào bảng bên dưới)</label>
            <p class="text-sm text-gray-500">Vui lòng chọn sinh viên trong bảng để cập nhật điểm thưởng</p>
        </div>
        <div class="pt-6">
            <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                <i class="fas fa-save mr-2"></i>Cập nhật hàng loạt
            </button>
        </div>
    </form>
</div>

{{-- Participants Table --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">
                        <a href="{{ route('admin.events.rewards.index', array_merge([$event->id], request()->except(['sort_by', 'sort_order']), [
                            'sort_by' => 'name',
                            'sort_order' => ($sortBy ?? 'reward_points') === 'name' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}" class="flex items-center gap-2 hover:text-gray-900">
                            <span>Họ và tên</span>
                            @if(($sortBy ?? 'reward_points') === 'name')
                                @if(($sortOrder ?? 'desc') === 'asc')
                                    <i class="fas fa-sort-up text-blue-600"></i>
                                @else
                                    <i class="fas fa-sort-down text-blue-600"></i>
                                @endif
                            @else
                                <i class="fas fa-sort text-gray-400"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">MSSV</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Lớp</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">
                        <a href="{{ route('admin.events.rewards.index', array_merge([$event->id], request()->except(['sort_by', 'sort_order']), [
                            'sort_by' => 'reward_points',
                            'sort_order' => ($sortBy ?? 'reward_points') === 'reward_points' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}" class="flex items-center gap-2 hover:text-gray-900">
                            <span>Điểm thưởng</span>
                            @if(($sortBy ?? 'reward_points') === 'reward_points')
                                @if(($sortOrder ?? 'desc') === 'asc')
                                    <i class="fas fa-sort-up text-blue-600"></i>
                                @else
                                    <i class="fas fa-sort-down text-blue-600"></i>
                                @endif
                            @else
                                <i class="fas fa-sort text-gray-400"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">
                        <a href="{{ route('admin.events.rewards.index', array_merge([$event->id], request()->except(['sort_by', 'sort_order']), [
                            'sort_by' => 'attended_at',
                            'sort_order' => ($sortBy ?? 'reward_points') === 'attended_at' && ($sortOrder ?? 'desc') === 'asc' ? 'desc' : 'asc'
                        ])) }}" class="flex items-center gap-2 hover:text-gray-900">
                            <span>Ngày điểm danh</span>
                            @if(($sortBy ?? 'reward_points') === 'attended_at')
                                @if(($sortOrder ?? 'desc') === 'asc')
                                    <i class="fas fa-sort-up text-blue-600"></i>
                                @else
                                    <i class="fas fa-sort-down text-blue-600"></i>
                                @endif
                            @else
                                <i class="fas fa-sort text-gray-400"></i>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($participants as $participant)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <input 
                                type="checkbox" 
                                name="user_ids[]" 
                                value="{{ $participant->user_id }}" 
                                class="participant-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500"
                            >
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-green-500 to-green-600 flex items-center justify-center text-white font-semibold mr-3">
                                    {{ strtoupper(substr($participant->user->name, 0, 1)) }}
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $participant->user->name }}
                                </div>
                            </div>
                        </td>
                         <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $participant->student_id }}
                        </td>
                         <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $participant->student_class }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $participant->user->email }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $participant->reward_points > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas fa-star mr-1"></i>{{ $participant->reward_points ?? 0 }} điểm
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $participant->attended_at ? $participant->attended_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.events.rewards.update', [$event->id, $participant->user_id]) }}" class="inline-flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input 
                                    type="number" 
                                    name="reward_points" 
                                    value="{{ $participant->reward_points ?? 0 }}" 
                                    min="0" 
                                    max="1000" 
                                    required
                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                >
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-trophy text-4xl mb-2 text-gray-300"></i>
                            <p>Chưa có sinh viên nào đã tham gia sự kiện này.</p>
                            <p class="text-sm mt-2">Sinh viên cần được điểm danh trước khi có thể nhận điểm thưởng.</p>
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

{{-- Bulk Update Form (Hidden) --}}
<form id="bulkUpdateForm" action="{{ route('admin.events.rewards.bulk-update', $event->id) }}" method="POST" style="display: none;">
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
    });

    // Update select all when individual checkbox changes
    document.querySelectorAll('.participant-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(document.querySelectorAll('.participant-checkbox')).every(cb => cb.checked);
            document.getElementById('selectAll').checked = allChecked;
        });
    });

    // Bulk update form submission
    document.querySelector('form[action*="bulk-update"]')?.addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.participant-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một sinh viên để cập nhật điểm thưởng!');
            return false;
        }
        
        // Add hidden inputs for selected user IDs
        checkedBoxes.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'user_ids[]';
            hiddenInput.value = checkbox.value;
            this.appendChild(hiddenInput);
        });
    });
</script>
@endpush

