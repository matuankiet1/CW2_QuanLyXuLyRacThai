{{-- 
    View: Danh sách nhiệm vụ được gán (Staff)
    Route: GET /staff/trash-requests
    Controller: TrashRequestController@staffIndex
--}}
@extends('layouts.user')

@section('title', 'Nhiệm vụ của tôi')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">📋 Nhiệm vụ thu gom rác</h1>
        <p class="text-gray-600 text-lg">
            @if(auth()->user()->isAdmin())
                Danh sách tất cả yêu cầu thu gom rác
            @else
                Danh sách nhiệm vụ được gán cho bạn
            @endif
        </p>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('staff.trash-requests.index', ['status' => 'all']) }}" 
               class="px-4 py-2 rounded-lg transition {{ ($status ?? 'all') === 'all' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Tất cả
            </a>
            <a href="{{ route('staff.trash-requests.index', ['status' => 'assigned']) }}" 
               class="px-4 py-2 rounded-lg transition {{ ($status ?? 'all') === 'assigned' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã gán
            </a>
            <a href="{{ route('staff.trash-requests.index', ['status' => 'staff_done']) }}" 
               class="px-4 py-2 rounded-lg transition {{ ($status ?? 'all') === 'staff_done' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Đã hoàn thành
            </a>
            <a href="{{ route('staff.trash-requests.index', ['status' => 'waiting_admin']) }}" 
               class="px-4 py-2 rounded-lg transition {{ ($status ?? 'all') === 'waiting_admin' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Chờ duyệt
            </a>
            <a href="{{ route('staff.trash-requests.index', ['status' => 'admin_rejected']) }}" 
               class="px-4 py-2 rounded-lg transition {{ ($status ?? 'all') === 'admin_rejected' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Bị từ chối
            </a>
        </div>
    </div>

    {{-- Danh sách nhiệm vụ --}}
    @if($trashRequests->count() > 0)
        <div class="space-y-4">
            @foreach($trashRequests as $request)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                                        <a href="{{ route('staff.trash-requests.show', $request->request_id) }}" 
                                           class="hover:text-green-600 transition">
                                            {{ $request->location }}
                                        </a>
                                    </h3>
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                        <span><i class="fas fa-tag mr-1 text-green-600"></i>{{ $request->type }}</span>
                                        <span><i class="fas fa-user mr-1 text-green-600"></i>{{ $request->student->name }}</span>
                                        @if($request->assignedStaff && auth()->user()->isAdmin())
                                            <span><i class="fas fa-user-tie mr-1 text-green-600"></i>Admin: {{ $request->assignedStaff->name }}</span>
                                        @endif
                                        <span><i class="fas fa-calendar mr-1 text-green-600"></i>{{ $request->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                <div>
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
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white {{ $statusColors[$request->status] ?? 'bg-gray-500' }}">
                                        {{ $statusTexts[$request->status] ?? $request->status }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($request->description)
                                <p class="text-gray-700 mb-3 line-clamp-2">{{ $request->description }}</p>
                            @endif
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('staff.trash-requests.show', $request->request_id) }}" 
                               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                <i class="fas fa-eye mr-1"></i>Xem chi tiết
                            </a>
                            @if(in_array($request->status, ['assigned', 'admin_rejected']) || (auth()->user()->isAdmin() && $request->status === 'assigned'))
                                <a href="{{ route('staff.trash-requests.edit', $request->request_id) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-edit mr-1"></i>Cập nhật
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $trashRequests->appends(request()->query())->links('pagination.tailwind') }}
        </div>
    @else
        {{-- Empty state --}}
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Chưa có nhiệm vụ nào</h3>
            <p class="text-gray-500">Bạn chưa được gán nhiệm vụ thu gom rác nào.</p>
        </div>
    @endif
</div>
@endsection

