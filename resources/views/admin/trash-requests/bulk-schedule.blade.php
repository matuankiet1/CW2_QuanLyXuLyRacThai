{{-- 
    View: Form chia lịch hàng loạt cho yêu cầu thu gom rác
    Route: GET /admin/trash-requests/bulk-schedule
    Controller: TrashRequestController@showBulkScheduleForm
--}}
@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container mx-auto px-4">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt mr-2"></i>Chia lịch hàng loạt
        </h2>
        <p class="text-gray-600 mt-1">Chọn nhân viên và ngày thu gom cho các yêu cầu đã chọn</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
            <span class="block sm:inline">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </span>
        </div>
    @endif

    {{-- Selected Requests List --}}
    <div class="bg-white rounded-lg shadow-md mb-4">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-lg">Danh sách yêu cầu đã chọn ({{ $trashRequests->count() }})</h3>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="px-4 py-2 text-left font-semibold">STT</th>
                            <th class="px-4 py-2 text-left font-semibold">Địa điểm</th>
                            <th class="px-4 py-2 text-left font-semibold">Loại rác</th>
                            <th class="px-4 py-2 text-left font-semibold">Người gửi</th>
                            <th class="px-4 py-2 text-left font-semibold">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashRequests as $index => $request)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $request->location }}</td>
                                <td class="px-4 py-2">{{ $request->type }}</td>
                                <td class="px-4 py-2">{{ $request->student->name }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $statusTexts = [
                                            'pending' => 'Đang chờ',
                                            'assigned' => 'Đã gán',
                                            'waiting_admin' => 'Chờ duyệt',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold text-white bg-blue-500">
                                        {{ $statusTexts[$request->status] ?? $request->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Schedule Form --}}
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <form method="POST" action="{{ route('admin.trash-requests.bulk-schedule') }}">
                @csrf
                
                {{-- Hidden inputs for request IDs --}}
                @foreach($trashRequests as $request)
                    <input type="hidden" name="request_ids[]" value="{{ $request->request_id }}">
                @endforeach

                {{-- Staff Selection --}}
                <div class="mb-6">
                    <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tie mr-2"></i>Chọn nhân viên <span class="text-red-500">*</span>
                    </label>
                    <select name="staff_id" id="staff_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('staff_id') border-red-500 @enderror">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->user_id }}" {{ old('staff_id') == $staff->user_id ? 'selected' : '' }}>
                                {{ $staff->name }} ({{ $staff->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('staff_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Scheduled Date --}}
                <div class="mb-6">
                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2"></i>Ngày thu gom <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="scheduled_date" id="scheduled_date" required
                        min="{{ date('Y-m-d') }}"
                        value="{{ old('scheduled_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('scheduled_date') border-red-500 @enderror">
                    @error('scheduled_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Ngày thu gom phải từ hôm nay trở đi</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.trash-requests.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition">
                        <i class="fas fa-times mr-2"></i>Hủy
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i>Xác nhận chia lịch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

