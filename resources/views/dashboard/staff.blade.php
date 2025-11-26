@extends('layouts.staff')

@section('title', 'Bảng điều khiển Nhân viên')

@section('content')
    <div class="p-6 space-y-6">
        <div class="bg-linear-to-r from-blue-500 to-blue-600 text-black rounded-xl shadow-lg p-6">
            <h1 class="text-3xl font-semibold mb-2">Xin chào Nhân viên</h1>
            <p class="text-black-100">Theo dõi nhiệm vụ được giao, thực hiện check-in và cập nhật tiến độ thu gom.</p>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Sự kiện được phân công</h2>
                <a href="{{ route('staff.collection_schedules.index') }}" class="text-sm text-blue-600 hover:underline">Báo
                    cáo thu gom</a>
            </div>

            <div class="overflow-x-auto" id="tableContainer">
                <table class="min-w-full border-t border-gray-100">
                    <thead class="bg-green-50 text-gray-600 text-sm font-semibold">
                        <tr>
                            <th class="py-3 px-4 text-left">Mã lịch thu gom</th>
                            <th class="py-3 px-4 text-left">Nhân viên thực hiện</th>
                            <th class="py-3 px-4 text-left">Ngày thu gom</th>
                            <th class="py-3 px-4 text-left">Ngày hoàn thành</th>
                            <th class="py-3 px-4 text-left">Trạng thái</th>
                            <th class="py-3 px-4 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse ($collectionSchedules as $collectionSchedule)
                            <tr class="hover:bg-green-50">
                                <td class="py-3 px-4">{{ $collectionSchedule->schedule_id }}</td>
                                <td class="py-3 px-4">{{ $collectionSchedule->staff->name }}</td>
                                <td class="py-3 px-4">
                                    {{ $collectionSchedule->scheduled_date?->format('Y-m-d') ?? '-' }}
                                </td>
                                <td class="py-3 px-4">{{ $collectionSchedule->completed_at?->format('Y-m-d') ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($collectionSchedule->status == 'Chưa thực hiện')
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                            {{ $collectionSchedule->status }}
                                        </span>
                                    @elseif($collectionSchedule->status == 'Đã hoàn thành')
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                            {{ $collectionSchedule->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <button data-id="{{ $collectionSchedule->schedule_id }}"
                                            class="addWasteLogBtn group inline-flex items-center justify-center hover:bg-amber-200 rounded-xl mx-1 p-2 transition cursor-pointer"
                                            data-modal="add" aria-label="Thêm">
                                            <svg class="w-5 h-5 group-hover:text-amber-600" viewBox="0 0 24 24" color="#254434"
                                                fill="none" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.862 3.487a1.75 1.75 0 0 1 2.476 2.476L8.5 16.8 4 18l1.2-4.5 11.662-10.013Z" />
                                            </svg>
                                        </button>

                                        {{-- <button data-id="{{ $collectionSchedule->schedule_id }}"
                                            class="editWasteLogBtn group inline-flex items-center justify-center hover:bg-amber-200 rounded-xl mx-1 p-2 transition cursor-pointer"
                                            data-modal="edit" aria-label="Chỉnh sửa">
                                            <svg class="w-5 h-5 group-hover:text-amber-600" viewBox="0 0 24 24" color="#254434"
                                                fill="none" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.862 3.487a1.75 1.75 0 0 1 2.476 2.476L8.5 16.8 4 18l1.2-4.5 11.662-10.013Z" />
                                            </svg>
                                        </button> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">
                                    @if ($isSearching == true)
                                        Không có kết quả tìm kiếm cho từ khóa
                                        "<strong class="text-gray-700">{{ $q }}</strong>".
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Tác vụ nhanh</h3>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li>• Check-in sự kiện được giao</li>
                        <li>• Điểm danh sinh viên bằng QR</li>
                        <li>• Cập nhật khối lượng rác và hình ảnh</li>
                        <li>• Gửi báo cáo cuối sự kiện</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Hỗ trợ</h3>
                    <p class="text-gray-600 text-sm">Liên hệ Quản lý nếu bạn cần được phân công hoặc có sự cố trong quá
                        trình
                        thu gom.</p>
                </div>
            </div>
        </div>
    </div>
    
@endsection