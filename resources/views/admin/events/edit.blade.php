@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3 xl:w-1/2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-4">🆕 Sửa sự kiện</h2>
                    <form method="POST" action="{{ route('admin.events.update', $event->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Tên sự kiện</label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            @error('title') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày bắt đầu đăng ký tham gia</label>
                                <input type="date" name="register_date" value="{{ old('register_date', $event->register_date) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('register_date') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày kết thúc đăng ký tham gia</label>
                                <input type="date" name="register_end_date" value="{{ old('register_end_date', $event->register_end_date) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('register_end_date') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày bắt đầu sự kiện</label>
                                <input type="date" name="event_start_date" value="{{ old('event_start_date', $event->event_start_date) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('event_start_date') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Ngày kết thúc sự kiện</label>
                                <input type="date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('event_end_date') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Địa điểm</label>
                                <input type="text" name="location" value="{{ old('location', $event->location) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('location') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Người tham gia</label>
                                <input type="text" name="participants" value="{{ old('participants', $event->participants) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                @error('participants') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="block text-sm font-medium mb-1">Mô tả sự kiện</label>
                            <input type="text" name="description" value="{{ old('description', $event->description) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            @error('description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Trạng thái</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option value="upcoming" {{ old('status', $event->status) == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                                <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.events.index') }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">⬅️ Quay lại</a>
                            <button type="submit" class="btn btn-admin">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
