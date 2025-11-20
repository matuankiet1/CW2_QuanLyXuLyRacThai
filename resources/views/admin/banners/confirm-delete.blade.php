@extends('layouts.admin-with-sidebar')

@section('title', 'Xác nhận xóa banner - Admin')

@section('content')
<div class="flex items-center justify-center h-[70vh]">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md text-center border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            ⚠️ Xác nhận xóa Banner
        </h2>
        <p class="text-gray-600 mb-6">
            Bạn có chắc chắn muốn xóa banner <strong>{{ $banner->title }}</strong> không?<br>
            Hành động này <span class="text-red-600 font-semibold">không thể hoàn tác</span>!
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('admin.banners.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                Hủy bỏ
            </a>

            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Xóa Banner
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
