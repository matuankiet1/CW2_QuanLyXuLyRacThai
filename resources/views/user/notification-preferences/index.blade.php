@extends('layouts.user')

@section('title', 'Cài đặt thông báo')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <h1 class="fw-bold mb-2">🔔 Cài đặt thông báo</h1>
                <p class="text-muted">Quản lý cách bạn nhận thông báo từ hệ thống</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('user.notification-preferences.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <h5 class="mb-3">Chọn cách bạn muốn nhận thông báo:</h5>
                            
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="email" value="1" id="email" {{ $preference->allowsEmail() ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email">
                                            <strong><i class="fas fa-envelope me-2"></i>Email Notifications</strong>
                                            <p class="text-muted small mb-0">Nhận thông báo qua email</p>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="push" value="1" id="push" {{ $preference->allowsPush() ? 'checked' : '' }}>
                                        <label class="form-check-label" for="push">
                                            <strong><i class="fas fa-bell me-2"></i>Push Notifications</strong>
                                            <p class="text-muted small mb-0">Nhận thông báo push trên thiết bị</p>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="in_app" value="1" id="in_app" {{ $preference->allowsInApp() ? 'checked' : '' }}>
                                        <label class="form-check-label" for="in_app">
                                            <strong><i class="fas fa-inbox me-2"></i>In-App Notifications</strong>
                                            <p class="text-muted small mb-0">Hiển thị thông báo trong ứng dụng</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.simple-notifications.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu cài đặt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

