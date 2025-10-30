@extends('layouts.dashboard')

@section('main-content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-4">🆕 Tạo sự kiện mới</h2>

    <form method="POST" action="{{ route('admin.events.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-medium mb-1">Tên sự kiện</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border p-2 rounded">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Ngày bắt đầu đăng ký tham gia</label>
            <input type="date" name="register_date" value="{{ old('register_date') }}" required class="w-full border p-2 rounded">
            @error('register_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Ngày kết thúc đăng ký tham gia</label>
            <input type="date" name="register_end_date" value="{{ old('register_end_date') }}" required class="w-full border p-2 rounded">
            @error('register_end_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Ngày bắt đầu sự kiện</label>
            <input type="date" name="event_start_date" value="{{ old('event_start_date') }}" required class="w-full border p-2 rounded">
            @error('event_start_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Ngày kết thúc sự kiện</label>
            <input type="date" name="event_end_date" value="{{ old('event_end_date') }}" required class="w-full border p-2 rounded">
            @error('event_end_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Địa điểm</label>
            <input type="text" name="location" value="{{ old('location') }}" required class="w-full border p-2 rounded">
            @error('location') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

          <div class="mb-4">
            <label class="block font-medium mb-1">Người tham gia</label>
            <input type="text" name="participants" value="{{ old('participants') }}" required class="w-full border p-2 rounded">
            @error('participants') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Mô tả sự kiện</label>
            <input type="text" name="description" value="{{ old('description') }}" required class="w-full border p-2 rounded">
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Trạng thái</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.events.index') }}" class="bg-gray-200 px-3 py-2 rounded">⬅️ Quay lại</a>
            <button type="submit" class="bg-blue-600 text-white px-3 py-2 rounded">Lưu</button>
        </div>
    </form>
</div>
@endsection
