@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Danh sách Banner</h2>

    <!-- Thanh tìm kiếm -->
    <form method="GET" action="{{ route('banners.index') }}" class="mb-4">
        <div style="display: flex; gap: 10px;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm banner..." class="form-control" style="flex:1;">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            <a href="{{ route('banners.create') }}" class="btn btn-success">+ Thêm mới</a>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên banner</th>
                <th>Liên kết</th>
                <th>Hình ảnh</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($banners as $banner)
                <tr>
                    <td>{{ $banner->name }}</td>
                    <td>{{ $banner->link }}</td>
                    <td>
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" width="100">
                        @else
                            Không có
                        @endif
                    </td>
                    <td>{{ $banner->description }}</td>
                    <td>
                        <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa banner này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Không có banner nào</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Phân trang -->
    {{ $banners->links() }}
</div>
@endsection
