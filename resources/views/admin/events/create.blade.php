@extends('layouts.admin-with-sidebar')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            <div class="card shadow-soft">
                <div class="card-body">
                    <h2 class="h4 mb-4">🆕 Tạo sự kiện mới</h2>
                    <form method="POST" action="{{ route('admin.events.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tên sự kiện</label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="form-control">
                            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Ngày bắt đầu đăng ký tham gia</label>
                                <input type="date" name="register_date" value="{{ old('register_date') }}" required class="form-control">
                                @error('register_date') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày kết thúc đăng ký tham gia</label>
                                <input type="date" name="register_end_date" value="{{ old('register_end_date') }}" required class="form-control">
                                @error('register_end_date') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày bắt đầu sự kiện</label>
                                <input type="date" name="event_start_date" value="{{ old('event_start_date') }}" required class="form-control">
                                @error('event_start_date') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày kết thúc sự kiện</label>
                                <input type="date" name="event_end_date" value="{{ old('event_end_date') }}" required class="form-control">
                                @error('event_end_date') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Địa điểm</label>
                                <input type="text" name="location" value="{{ old('location') }}" required class="form-control">
                                @error('location') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Người tham gia</label>
                                <input type="text" name="participants" value="{{ old('participants') }}" required class="form-control">
                                @error('participants') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3 mt-1">
                            <label class="form-label">Mô tả sự kiện</label>
                            <input type="text" name="description" value="{{ old('description') }}" required class="form-control">
                            @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">⬅️ Quay lại</a>
                            <button type="submit" class="btn btn-admin">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
