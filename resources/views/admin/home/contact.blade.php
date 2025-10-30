@extends('layouts.admin')

@section('title', 'Liên hệ - EcoWaste Admin')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">
                        Liên hệ hỗ trợ
                    </h1>
                    <p class="lead mb-4">
                        Cần hỗ trợ kỹ thuật hoặc có câu hỏi về hệ thống? Chúng tôi luôn sẵn sàng hỗ trợ bạn.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-headset" style="font-size: 8rem; color: rgba(255,255,255,0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body p-5">
                        <h2 class="section-title text-center mb-5">Thông tin liên hệ</h2>
                        
                        <div class="row mb-5">
                            <div class="col-md-4 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h5>Email hỗ trợ</h5>
                                    <p class="text-muted">admin@ecowaste.com</p>
                                    <a href="mailto:admin@ecowaste.com" class="btn btn-outline-primary btn-sm">Gửi email</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <h5>Điện thoại</h5>
                                    <p class="text-muted">+84 123 456 789</p>
                                    <a href="tel:+84123456789" class="btn btn-outline-primary btn-sm">Gọi ngay</a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h5>Địa chỉ</h5>
                                    <p class="text-muted">123 Đường ABC, Quận XYZ, TP.HCM</p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Xem bản đồ</a>
                                </div>
                            </div>
                        </div>

                        <h3 class="mb-4">Gửi tin nhắn hỗ trợ</h3>
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="name" value="{{ auth()->user()->name }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Chủ đề</label>
                                <select class="form-select" id="subject">
                                    <option value="">Chọn chủ đề hỗ trợ</option>
                                    <option value="technical">Hỗ trợ kỹ thuật</option>
                                    <option value="feature">Yêu cầu tính năng mới</option>
                                    <option value="bug">Báo lỗi hệ thống</option>
                                    <option value="account">Vấn đề tài khoản</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="message" rows="5" placeholder="Mô tả chi tiết vấn đề hoặc yêu cầu của bạn..."></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                                </button>
                            </div>
                        </form>

                        <div class="mt-5 p-4 bg-light rounded">
                            <h4 class="mb-3">Thời gian hỗ trợ</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Thứ 2 - Thứ 6:</strong> 8:00 - 17:00</p>
                                    <p class="mb-2"><strong>Thứ 7:</strong> 8:00 - 12:00</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Chủ nhật:</strong> Nghỉ</p>
                                    <p class="mb-0"><strong>Hỗ trợ khẩn cấp:</strong> 24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Links -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Liên kết nhanh</h2>
                <p class="section-subtitle">Truy cập nhanh các chức năng quản lý</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h5 class="card-title">Dashboard</h5>
                        <p class="card-text">Tổng quan hệ thống và thống kê</p>
                        <a href="{{ route('dashboard.admin') }}" class="btn btn-primary">Truy cập</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Quản lý người dùng</h5>
                        <p class="card-text">Quản lý tài khoản và thông tin</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Quản lý</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="card-title">Báo cáo</h5>
                        <p class="card-text">Xem báo cáo và thống kê chi tiết</p>
                        <a href="{{ route('reports.index') }}" class="btn btn-primary">Xem báo cáo</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5 class="card-title">Cài đặt</h5>
                        <p class="card-text">Cấu hình hệ thống và phân quyền</p>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-primary">Cài đặt</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Cần hỗ trợ thêm?</h3>
                <p class="mb-0">Liên hệ với chúng tôi qua email hoặc điện thoại để được hỗ trợ tốt nhất.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="mailto:admin@ecowaste.com" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>Gửi email hỗ trợ
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
