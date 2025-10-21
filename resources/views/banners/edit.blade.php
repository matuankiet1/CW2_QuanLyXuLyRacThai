@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Sửa banner</h3>

    <form action="{{ route('banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Tên banner</label>
            <input type="text" name="name" class="form-control" value="{{ $banner->name }}" required>
        </div>

        <div class="mb-3">
            <label>Liên kết</label>
            <input type="text" name="link" class="form-control" value="{{ $banner->link }}">
        </div>

        <div class="mb-3">
            <label>Hình ảnh hiện tại</label><br>
            @if ($banner->image)
                <img src="{{ asset('storage/'.$banner->image) }}" width="120">
            @endif
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ $banner->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
