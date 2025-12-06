{{-- 
    View: Chi tiết yêu cầu thu gom rác (Admin)
    Route: GET /admin/trash-requests/{id}
    Controller: TrashRequestController@adminShow
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

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.trash-requests.index') }}" 
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
            {{-- Thông tin yêu cầu --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin yêu cầu</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Người gửi</label>
                        <p class="text-gray-900 font-medium">{{ $trashRequest->student->name }}</p>
                        <p class="text-sm text-gray-600">{{ $trashRequest->student->email }}</p>
                    </div>
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

            {{-- Ảnh từ student --}}
            @if($trashRequest->image)
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ảnh minh chứng từ người gửi</h2>
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
                        <p class="text-sm text-gray-600 mt-1">{{ $trashRequest->assignedStaff->email }}</p>
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

            {{-- Admin Actions cho trạng thái assigned --}}
            @if($trashRequest->status === 'assigned')
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Thao tác</h2>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <p class="text-sm text-blue-800 mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            Yêu cầu đã được gán. Bạn có thể cập nhật thông tin thu gom hoặc duyệt trực tiếp nếu đã kiểm tra.
                        </p>
                        <div class="flex gap-4">
                            <a href="{{ route('staff.trash-requests.edit', $trashRequest->request_id) }}" 
                               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                <i class="fas fa-edit mr-2"></i>Cập nhật thông tin thu gom
                            </a>
                            <button type="button" 
                                    onclick="document.getElementById('quickApproveForm').classList.toggle('hidden')"
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                <i class="fas fa-check mr-2"></i>Duyệt trực tiếp
                            </button>
                        </div>
                        
                        {{-- Form duyệt nhanh (ẩn mặc định) --}}
                        <div id="quickApproveForm" class="hidden mt-4 pt-4 border-t border-blue-300">
                            <form action="{{ route('admin.trash-requests.approve', $trashRequest->request_id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="quick_approve_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ghi chú (tùy chọn)
                                    </label>
                                    <textarea id="quick_approve_notes" 
                                              name="admin_notes" 
                                              rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                              placeholder="Ghi chú khi duyệt...">{{ old('admin_notes') }}</textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" 
                                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                        <i class="fas fa-check mr-2"></i>Xác nhận duyệt
                                    </button>
                                    <button type="button" 
                                            onclick="document.getElementById('quickApproveForm').classList.add('hidden')"
                                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                        Hủy
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Admin Actions (chỉ hiển thị khi đang chờ duyệt) --}}
            @if($trashRequest->isWaitingAdmin())
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Duyệt yêu cầu</h2>
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <form action="{{ route('admin.trash-requests.approve', $trashRequest->request_id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-4">
                                <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ghi chú (tùy chọn)
                                </label>
                                <textarea id="approve_notes" 
                                          name="admin_notes" 
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                          placeholder="Ghi chú khi duyệt...">{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" 
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                <i class="fas fa-check mr-2"></i>Duyệt yêu cầu
                            </button>
                        </form>

                        <form action="{{ route('admin.trash-requests.reject', $trashRequest->request_id) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn từ chối yêu cầu này?');">
                            @csrf
                            <div class="mb-4">
                                <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Lý do từ chối <span class="text-red-500">*</span>
                                </label>
                                <textarea id="reject_notes" 
                                          name="admin_notes" 
                                          rows="3"
                                          required
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                          placeholder="Nhập lý do từ chối...">{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" 
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                <i class="fas fa-times mr-2"></i>Từ chối yêu cầu
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Thông tin từ admin (nếu đã duyệt/từ chối) --}}
            @if($trashRequest->admin_notes || $trashRequest->admin_reviewed_at)
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin duyệt</h2>
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

