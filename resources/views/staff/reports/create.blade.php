@extends('layouts.staff')

@section('title', 'Gửi báo cáo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-flag me-2"></i>Gửi báo cáo
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('user.reports.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Tiêu đề báo cáo <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   placeholder="Ví dụ: Vấn đề về thu gom rác thải..." 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold">
                                Loại báo cáo <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required>
                                <option value="">-- Chọn loại báo cáo --</option>
                                <option value="complaint" {{ old('type') == 'complaint' ? 'selected' : '' }}>
                                    Khiếu nại
                                </option>
                                <option value="suggestion" {{ old('type') == 'suggestion' ? 'selected' : '' }}>
                                    Đề xuất
                                </option>
                                <option value="bug" {{ old('type') == 'bug' ? 'selected' : '' }}>
                                    Lỗi hệ thống
                                </option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>
                                    Khác
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Nội dung báo cáo <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="6" 
                                      placeholder="Mô tả chi tiết vấn đề, đề xuất hoặc phản hồi của bạn..." 
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Vui lòng cung cấp thông tin chi tiết và cụ thể để chúng tôi có thể xử lý nhanh chóng.
                            </small>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Gửi báo cáo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Thông tin hỗ trợ -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-question-circle me-2 text-primary"></i>Thông tin hữu ích
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-semibold">
                                <i class="fas fa-lightbulb me-2 text-warning"></i>Khiếu nại
                            </h6>
                            <p class="text-muted small">
                                Báo cáo về các vấn đề gặp phải trong quá trình sử dụng dịch vụ hoặc hệ thống.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold">
                                <i class="fas fa-comments me-2 text-info"></i>Đề xuất
                            </h6>
                            <p class="text-muted small">
                                Chia sẻ ý tưởng, đề xuất để cải thiện hệ thống và dịch vụ.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
