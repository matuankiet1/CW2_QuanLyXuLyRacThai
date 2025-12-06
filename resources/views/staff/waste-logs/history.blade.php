@extends('layouts.staff')

@section('title', 'Lịch sử báo cáo')

@section('content')

    <div class="p-6 space-y-6 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Lịch sử báo cáo</h2>
            <a href="{{ route('staff.waste-logs.index') }}"
                class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">
                ← Quay lại danh sách
            </a>
        </div>

        {{-- Thanh tìm kiếm --}}
        <form action="{{ route('staff.waste-logs.history') }}" method="GET" class="relative max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m21 21-4.35-4.35m1.1-4.4a7.75 7.75 0 1 1-15.5 0 7.75 7.75 0 0 1 15.5 0Z" />
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Tìm theo ID lịch, ghi chú..."
                class="w-full pl-11 pr-3 py-2 rounded-xl border border-gray-300 
                           focus:ring-2 focus:ring-green-400 outline-none transition">
        </form>

        {{-- Nếu không có báo cáo --}}
        @if ($logs->isEmpty())
            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200 text-yellow-800">
                <p>Không có báo cáo nào trong lịch sử.</p>
            </div>
        @endif

        {{-- Danh sách báo cáo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($logs as $log)
                <div class="p-4 bg-white rounded-xl shadow border border-gray-100 hover:shadow-md transition">

                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-semibold text-lg text-green-700">
                            Báo cáo #{{ $log->id }}
                        </h3>
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                            {{ $log->status }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600">
                        <strong>Tên:</strong> {{ $log->collectionSchedule->staff->name ?? '—' }}
                    </p>

                    <p class="text-sm text-gray-600">
                        <strong>ID lịch:</strong> {{ $log->collectionSchedule->schedule_id }}
                    </p>

                    <p class="text-sm text-gray-600">
                        <strong>Đã xác nhận vào lúc:</strong>
                        {{ $log->status === 'Đã xác nhận' ? optional($log->confirmed_at)->format('d/m/Y H:i') : '—' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Tổng lượng rác thu gom:</strong>
                            {{ number_format(\App\Models\WasteLog::sum('waste_weight'), 2) }}
                    </p>
                    @if ($log->note)
                        <p class="text-sm text-gray-600 mt-2">
                            <strong>Ghi chú:</strong> {{ $log->note }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Phân trang --}}
        <div>
            {{ $logs->links() }}
        </div>

    </div>

@endsection