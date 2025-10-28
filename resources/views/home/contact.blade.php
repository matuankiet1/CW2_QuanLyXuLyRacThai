@extends('layouts.user')

@section('title', 'Liên hệ - EcoWaste')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">Liên hệ với chúng tôi</h1>
                <p class="lead">
                    Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-5">
                        <h2 class="mb-4">Gửi tin nhắn cho chúng tôi</h2>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Họ và tên *</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Chủ đề *</label>
                                    <select class="form-select" id="subject" required>
                                        <option value="">Chọn chủ đề</option>
                                        <option value="general">Thông tin chung</option>
                                        <option value="support">Hỗ trợ kỹ thuật</option>
                                        <option value="partnership">Hợp tác</option>
                                        <option value="feedback">Góp ý</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Nội dung tin nhắn *</label>
                                    <textarea class="form-control" id="message" rows="6" required></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="privacy" required>
                                        <label class="form-check-label" for="privacy">
                                            Tôi đồng ý với <a href="#" class="text-primary">chính sách bảo mật</a> và 
                                            <a href="#" class="text-primary">điều khoản sử dụng</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h3 class="mb-4">Thông tin liên hệ</h3>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Địa chỉ</h6>
                                    <p class="text-muted mb-0">
                                        123 Đường ABC, Quận XYZ<br>
                                        Thành phố Hồ Chí Minh, Việt Nam
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Điện thoại</h6>
                                    <p class="text-muted mb-0">
                                        <a href="tel:+84123456789" class="text-decoration-none">
                                            +84 123 456 789
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Email</h6>
                                    <p class="text-muted mb-0">
                                        <a href="mailto:info@ecowaste.com" class="text-decoration-none">
                                            info@ecowaste.com
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Giờ làm việc</h6>
                                    <p class="text-muted mb-0">
                                        Thứ 2 - Thứ 6: 8:00 - 17:00<br>
                                        Thứ 7: 8:00 - 12:00<br>
                                        Chủ nhật: Nghỉ
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h6 class="mb-3">Theo dõi chúng tôi</h6>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Câu hỏi thường gặp</h2>
            <p class="section-subtitle">Những câu hỏi phổ biến về dịch vụ của chúng tôi</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Làm thế nào để đăng ký sử dụng dịch vụ?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Bạn có thể đăng ký tài khoản miễn phí bằng cách nhấn vào nút "Đăng ký" 
                                ở góc trên bên phải trang web. Sau khi đăng ký, bạn sẽ có thể truy cập 
                                đầy đủ các tính năng của hệ thống.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Hệ thống có tính phí không?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Hiện tại, hệ thống EcoWaste hoàn toàn miễn phí cho tất cả người dùng. 
                                Chúng tôi cam kết cung cấp dịch vụ chất lượng cao mà không tính phí.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Làm thế nào để xem lịch thu gom rác thải?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sau khi đăng nhập, bạn có thể xem lịch thu gom rác thải trong phần 
                                "Lịch thu gom" trên trang chủ. Lịch sẽ được cập nhật thường xuyên 
                                để đảm bảo thông tin chính xác.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Tôi có thể đóng góp bài viết không?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Có, chúng tôi khuyến khích người dùng đóng góp bài viết về môi trường 
                                và xử lý rác thải. Bạn có thể liên hệ với chúng tôi qua form liên hệ 
                                để được hướng dẫn cách đóng góp nội dung.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Form validation and submission
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        const privacy = document.getElementById('privacy').checked;
        
        // Basic validation
        if (!name || !email || !subject || !message || !privacy) {
            alert('Vui lòng điền đầy đủ thông tin và đồng ý với điều khoản sử dụng.');
            return;
        }
        
        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Vui lòng nhập địa chỉ email hợp lệ.');
            return;
        }
        
        // Simulate form submission
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
            this.reset();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    });
</script>
@endpush
