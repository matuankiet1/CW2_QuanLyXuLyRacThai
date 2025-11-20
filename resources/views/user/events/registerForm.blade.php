@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Đăng ký sự kiện</h2>

    <form action="{{ route('event_user.store') }}" method="POST">
        @csrf

        <!-- User ID (ẩn) -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <!-- Event ID -->
        <div class="mb-4">
            <label for="event_id" class="block text-sm font-medium text-gray-700">Chọn sự kiện</label>
            <select name="event_id" id="event_id" class="mt-1 block w-full border-gray-300 rounded">
                @foreach($events as $event)
                    <option value="{{ $event->id }}">
                        {{ $event->title }} ({{ $event->event_start_date->format('d/m/Y') }})
                    </option>
                @endforeach
            </select>
            @error('event_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Đăng ký
        </button>
    </form>
</div>
@endsection
