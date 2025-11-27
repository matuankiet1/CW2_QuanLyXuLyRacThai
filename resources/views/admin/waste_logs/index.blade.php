@extends('layouts.admin-with-sidebar')

@section('content')
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="bg-white rounded-lg shadow-md h-full">
                <div class="p-4 flex items-center gap-3">
                    <div class="rounded bg-green-100 text-green-600 flex items-center justify-center"
                        style="width:48px;height:48px;">🗂️</div>
                    <div>
                        <div class="text-gray-500 text-sm">Tổng bản ghi rác</div>
                        <div class="text-2xl font-semibold mb-0">{{ \App\Models\WasteLog::count() }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md h-full">
                <div class="p-4 flex items-center gap-3">
                    <div class="rounded bg-blue-100 text-blue-600 flex items-center justify-center"
                        style="width:48px;height:48px;">⚖️</div>
                    <div>
                        <div class="text-gray-500 text-sm">Tổng khối lượng (kg)</div>
                        <div class="text-2xl font-semibold mb-0">
                            {{ number_format(\App\Models\WasteLog::sum('waste_weight'), 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md h-full">
                <div class="p-4 flex items-center gap-3">
                    <div class="rounded bg-yellow-100 text-yellow-600 flex items-center justify-center"
                        style="width:48px;height:48px;">📅</div>
                    <div>
                        <div class="text-gray-500 text-sm">Lịch thu gom</div>
                        <div class="text-lg font-semibold mb-0">Theo dõi gần đây</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md mb-4">
            <div class="p-4">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium mb-1">Tìm kiếm</label>
                        <input type="text" name="q" value="{{ request('q') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Tìm theo loại rác, lịch...">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium mb-1">Schedule</label>
                        <select name="schedule_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Tất cả lịch</option>
                            @foreach(\App\Models\CollectionSchedule::latest()->limit(50)->get() as $s)
                                <option value="{{ $s->id }}" {{ request('schedule_id') == $s->id ? 'selected' : '' }}>
                                    #{{ $s->id }} - {{ optional($s->date)->format('d/m/Y') ?? $s->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium mb-1">Loại rác</label>
                        <select name="waste_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Tất cả</option>
                            @foreach(\App\Models\WasteType::all() as $wt)
                                <option value="{{ $wt->id }}" {{ request('waste_type_id') == $wt->id ? 'selected' : '' }}>
                                    {{ $wt->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse table-auto">
                    <thead>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left font-semibold" style="width:80px">STT</th>
                            <th class="px-4 py-3 text-left font-semibold">Schedule</th>
                            <th class="px-4 py-3 text-left font-semibold">Loại rác</th>
                            <th class="px-4 py-3 text-left font-semibold">Khối lượng (kg)</th>
                            <th class="px-4 py-3 text-left font-semibold">Ảnh</th>
                            <th class="px-4 py-3 text-left font-semibold">Ghi chú</th>
                            <th class="px-4 py-3 text-left font-semibold">Người tạo</th>
                            <th class="px-4 py-3 text-left font-semibold">Ngày tạo</th>
                            <th class="px-4 py-3 text-right font-semibold">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wasteLogs as $index => $log)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 text-center">{{ $wasteLogs->firstItem() + $index }}</td>
                                <td class="px-4 py-3">#{{ $log->schedule_id }} @if($log->schedule) -
                                {{ optional($log->schedule->date)->format('d/m/Y') ?? '' }} @endif
                                </td>
                                <td class="px-4 py-3">{{ optional($log->wasteType)->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ number_format($log->waste_weight, 2) }}</td>
                                <td class="px-4 py-3">
                                    @if($log->waste_image)
                                        <a href="{{ asset('storage/' . $log->waste_image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $log->waste_image) }}"
                                                class="w-20 h-14 object-cover rounded" alt="img">
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Không có</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $log->note ?? '-' }}</td>
                                <td class="px-4 py-3">{{ optional($log->collectionSchedule->staff)->name ?? 'System' }}</td>
                                <td class="px-4 py-3">{{ optional($log->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($log->status != 'Đã xác nhận')
                                        <form action="{{ route('admin.waste_logs.confirm', $log->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm">
                                                Xác nhận
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-green-600 font-semibold">Đã xác nhận</span>
                                        <br>
                                        <small class="text-gray-500">
                                            bởi {{ optional($log->confirmedBy)->name ?? 'System' }} <br>
                                            {{ optional($log->confirmed_at)->format('d/m/Y H:i') }}
                                        </small>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-4">Không có bản ghi nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t pt-4 bg-white px-4 pb-4">{{ $wasteLogs->withQueryString()->links() }}</div>
        </div>
    </div>
@endsection