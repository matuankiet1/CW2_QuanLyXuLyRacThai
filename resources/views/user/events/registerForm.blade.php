@extends('layouts.user') {{-- Giống layout admin nhưng dành cho user --}}

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full lg:w-2/3 xl:w-1/2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">📝 Đăng ký sự kiện</h2>

                    <form action="{{ route('user.events.register', $event->id) }}" method="POST">
                        @csrf

                        {{-- Thông tin sinh viên --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Họ và tên</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Mã số sinh viên</label>
                                <input type="text" name="mssv" value="" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Lớp</label>
                                <input type="text" name="class" value="" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        </div>

                        {{-- Event --}}
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Sự kiện</label>
                            <input type="text" value="{{ $event->title }} ({{ $event->event_start_date->format('d/m/Y') }} - {{ $event->event_end_date->format('d/m/Y') }})"
                                   disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                        </div>

                        {{-- Trạng thái và thời gian (ẩn) --}}
                        <input type="hidden" name="status" value="pending">
                        <input type="hidden" name="registered_at" value="{{ now() }}">
                        <input type="hidden" name="confirmed_at" value="">
                        <input type="hidden" name="attended_at" value="">

                        <div class="flex justify-end gap-2 mt-4">
                            <a href="{{ route('user.events.show', $event->id) }}"
                               class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">⬅️ Quay lại</a>
                            <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Xác nhận
                            </button>
                        </div>
                    </form>

                    {{-- Thông báo lỗi / success --}}
                    @if(session('error'))
                        <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
