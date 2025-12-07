{{-- 
    View: Chi tiết yêu cầu thu gom rác (Student)
    Route: GET /student/trash-requests/{id}
    Controller: TrashRequestController@studentShow
--}}
@extends('layouts.user')

@section('title', 'Chi tiết yêu cầu thu gom rác')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
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

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('student.trash-requests.index') }}" 
           class="text-green-600 hover:text-green-700 transition">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
        </a>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        {{-- Header --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $trashRequest->location }}</h1>
                <p class="text-gray-600">
                    <i class="fas fa-calendar mr-1"></i>
                    Tạo lúc: {{ $trashRequest->created_at->format('d/m/Y H:i') }}
                </p>
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
                <span class="px-4 py-2 rounded-full text-sm font-semibold text-white {{ $statusColors[$trashRequest->status] ?? 'bg-gray-500' }}">
                    {{ $statusTexts[$trashRequest->status] ?? $trashRequest->status }}
                </span>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6 space-y-6">
            {{-- Thông tin cơ bản --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin yêu cầu</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Loại rác</label>
                        <p class="text-gray-900 font-medium">{{ $trashRequest->type }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Địa điểm</label>
                        <p class="text-gray-900 font-medium">{{ $trashRequest->location }}</p>
                    </div>
                    @if($trashRequest->description)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Mô tả</label>
                            <p class="text-gray-900">{{ $trashRequest->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Ảnh minh chứng từ student --}}
            @if($trashRequest->image)
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ảnh minh chứng</h2>
                    <img src="{{ asset('storage/' . $trashRequest->image) }}" 
                         alt="Ảnh minh chứng" 
                         class="max-w-full h-auto rounded-lg shadow-md max-h-96">
                </div>
            @endif

            {{-- Thông tin staff --}}
            @if($trashRequest->assignedStaff)
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Nhân viên được gán</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900 font-medium">
                            <i class="fas fa-user-tie mr-2 text-green-600"></i>
                            {{ $trashRequest->assignedStaff->name }}
                        </p>
                        @if($trashRequest->assigned_at)
                            <p class="text-sm text-gray-600 mt-1">
                                Gán lúc: {{ $trashRequest->assigned_at->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Thông tin từ staff --}}
            @if($trashRequest->proof_image || $trashRequest->staff_notes)
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin từ nhân viên</h2>
                    @if($trashRequest->proof_image)
                        <div class="mb-4">
                            <label class="text-sm font-medium text-gray-500 block mb-2">Ảnh minh chứng</label>
                            <img src="{{ asset('storage/' . $trashRequest->proof_image) }}" 
                                 alt="Ảnh minh chứng từ staff" 
                                 class="max-w-full h-auto rounded-lg shadow-md max-h-96">
                        </div>
                    @endif
                    @if($trashRequest->staff_notes)
                        <div>
                            <label class="text-sm font-medium text-gray-500 block mb-2">Ghi chú</label>
                            <p class="text-gray-900 bg-gray-50 rounded-lg p-4">{{ $trashRequest->staff_notes }}</p>
                        </div>
                    @endif
                    @if($trashRequest->staff_completed_at)
                        <p class="text-sm text-gray-600 mt-2">
                            Hoàn thành lúc: {{ $trashRequest->staff_completed_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            @endif

            {{-- Thông tin từ admin --}}
            @if($trashRequest->admin_notes || $trashRequest->admin_reviewed_at)
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin từ quản trị viên</h2>
                    @if($trashRequest->admin_notes)
                        <div class="bg-{{ $trashRequest->isRejected() ? 'red' : 'green' }}-50 rounded-lg p-4">
                            <p class="text-gray-900">{{ $trashRequest->admin_notes }}</p>
                        </div>
                    @endif
                    @if($trashRequest->admin_reviewed_at)
                        <p class="text-sm text-gray-600 mt-2">
                            Duyệt lúc: {{ $trashRequest->admin_reviewed_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

