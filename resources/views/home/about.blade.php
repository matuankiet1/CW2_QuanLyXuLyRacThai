@extends('layouts.user')

@section('title', 'Giới thiệu - EcoWaste')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Về chúng tôi</h1>
                <p class="lead">
                    EcoWaste - Hệ thống quản lý xử lý rác thải thông minh, 
                    góp phần xây dựng tương lai bền vững
                </p>
            </div>
        </div>
    </div>
</section>

<!-- About Content -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body p-5">
                        <h2 class="mb-4">Sứ mệnh của chúng tôi</h2>
                        <p class="lead text-muted mb-4">
                            EcoWaste được ra đời với sứ mệnh tạo ra một hệ thống quản lý rác thải 
                            thông minh, hiệu quả và thân thiện với môi trường.
                        </p>
                        
                        <h3 class="mb-3">Tầm nhìn</h3>
                        <p class="mb-4">
                            Chúng tôi mong muốn trở thành nền tảng hàng đầu trong việc quản lý 
                            và xử lý rác thải tại Việt Nam, góp phần giảm thiểu tác động tiêu cực 
                            đến môi trường và xây dựng một cộng đồng bền vững.
                        </p>
                        
                        <h3 class="mb-3">Giá trị cốt lõi</h3>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-leaf"></i>
                                    </div>
                                    <div>
                                        <h5>Bền vững</h5>
                                        <p class="text-muted mb-0">
                                            Cam kết bảo vệ môi trường và phát triển bền vững
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div>
                                        <h5>Đổi mới</h5>
                                        <p class="text-muted mb-0">
                                            Áp dụng công nghệ tiên tiến trong quản lý rác thải
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h5>Cộng đồng</h5>
                                        <p class="text-muted mb-0">
                                            Khuyến khích sự tham gia tích cực của cộng đồng
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <h5>Tin cậy</h5>
                                        <p class="text-muted mb-0">
                                            Đảm bảo tính minh bạch và đáng tin cậy
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="mb-3">Chúng tôi làm gì?</h3>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="bg-light rounded-circle p-4 mb-3 d-inline-block">
                                        <i class="fas fa-calendar-alt text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5>Quản lý lịch thu gom</h5>
                                    <p class="text-muted">
                                        Tạo và quản lý lịch thu gom rác thải một cách khoa học và hiệu quả
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="bg-light rounded-circle p-4 mb-3 d-inline-block">
                                        <i class="fas fa-newspaper text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5>Thông tin giáo dục</h5>
                                    <p class="text-muted">
                                        Cung cấp thông tin và hướng dẫn về xử lý rác thải đúng cách
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="bg-light rounded-circle p-4 mb-3 d-inline-block">
                                        <i class="fas fa-chart-line text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5>Báo cáo thống kê</h5>
                                    <p class="text-muted">
                                        Theo dõi và phân tích hiệu quả hoạt động thu gom rác thải
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Đội ngũ của chúng tôi</h2>
            <p class="section-subtitle">Những con người tâm huyết với môi trường</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle p-4 mb-3 d-inline-block">
                            <i class="fas fa-user" style="font-size: 2rem;"></i>
                        </div>
                        <h5>Nguyễn Văn A</h5>
                        <p class="text-primary mb-2">Giám đốc điều hành</p>
                        <p class="text-muted small">
                            Chuyên gia về quản lý môi trường với hơn 10 năm kinh nghiệm
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle p-4 mb-3 d-inline-block">
                            <i class="fas fa-user" style="font-size: 2rem;"></i>
                        </div>
                        <h5>Trần Thị B</h5>
                        <p class="text-primary mb-2">Trưởng phòng kỹ thuật</p>
                        <p class="text-muted small">
                            Chuyên gia công nghệ thông tin và phát triển hệ thống
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <div class="bg-primary text-white rounded-circle p-4 mb-3 d-inline-block">
                            <i class="fas fa-user" style="font-size: 2rem;"></i>
                        </div>
                        <h5>Lê Văn C</h5>
                        <p class="text-primary mb-2">Chuyên viên môi trường</p>
                        <p class="text-muted small">
                            Chuyên gia về xử lý rác thải và bảo vệ môi trường
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4">Hãy liên hệ với chúng tôi!</h2>
                <p class="lead mb-4">
                    Bạn có câu hỏi hoặc muốn tìm hiểu thêm về dịch vụ của chúng tôi? 
                    Chúng tôi luôn sẵn sàng hỗ trợ bạn.
                </p>
                <a href="{{ route('home.contact') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope me-2"></i>Liên hệ ngay
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
