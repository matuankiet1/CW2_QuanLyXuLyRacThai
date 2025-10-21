@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Thêm banner</h3>

    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tên banner</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Liên kết</label>
            <input type="text" name="link" class="form-control">
        </div>

        <div class="mb-3">
            <label>Hình ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('banners.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
