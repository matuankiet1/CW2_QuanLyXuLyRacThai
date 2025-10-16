{{-- resources/views/events/index.blade.php --}}
@extends('layouts.dashboard') {{-- Giả sử bạn có một layout dashboard --}}

@section('content')
<div class="space-y-6">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card: Tổng sự kiện --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng sự kiện</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalEvents }}</h3>
                </div>
            </div>
        </div>
        {{-- Card: Tổng người tham gia --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.122-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.122-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng người tham gia</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalParticipants) }}</h3>
                </div>
            </div>
        </div>
        {{-- Card: Rác thu gom --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                     <svg class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rác thu gom</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalWaste) }} kg</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('events.index') }}" class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" placeholder="Tìm kiếm sự kiện..." value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </form>

            {{-- "Create Event" Button/Modal --}}
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2"
                    onclick="document.getElementById('createEventModal').showModal()">
                 <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tạo sự kiện mới
            </button>
        </div>

        {{-- Events Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sự kiện</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa điểm</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người tham gia</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rác thu gom</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($events as $event)
                        <tr class="border-b">
                            <td class="py-3 px-4">{{ $event->title }}</td>
                            <td class="py-3 px-4">{{ $event->date->format('d/m/Y') }}</td>
                            <td class="py-3 px-4">{{ $event->location }}</td>
                            <td class="py-3 px-4">{{ $event->participants }} người</td>
                            <td class="py-3 px-4">{{ $event->waste > 0 ? $event->waste . ' kg' : '-' }}</td>
                            <td class="py-3 px-4">
                                @if ($event->status === 'completed')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Đã hoàn thành
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Sắp diễn ra
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('events.edit', $event) }}" class="p-2 text-blue-600 hover:text-blue-900">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sự kiện này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-900">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Không tìm thấy sự kiện nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="mt-6">
            {{ $events->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- Modal for creating a new event --}}
@include('events.partials.create-modal')
@endsection