@extends('layouts.admin-with-sidebar')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 fw-bold">Dashboard</h1>
        <span class="badge bg-success">Bản thử nghiệm</span>
    </div>

    <!-- Metrics Cards -->
    <div class="row g-4 mb-4">
        <!-- Tổng rác thu gom tháng này -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-trash-alt text-primary fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success">↑ 8.3%</span>
                    </div>
                    <h6 class="text-muted mb-2 small text-uppercase">Tổng rác thu gom tháng này</h6>
                    <h2 class="mb-0 fw-bold">534 <span class="fs-6 text-muted">kg</span></h2>
                    <small class="text-muted d-block mt-2">↑ 8.3% so với tháng trước</small>
                </div>
            </div>
        </div>

        <!-- Sinh viên tham gia -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-users text-info fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success">↑ 12.8%</span>
                    </div>
                    <h6 class="text-muted mb-2 small text-uppercase">Sinh viên tham gia</h6>
                    <h2 class="mb-0 fw-bold">167</h2>
                    <small class="text-muted d-block mt-2">↑ 12.8% so với tháng trước</small>
                </div>
            </div>
        </div>

        <!-- Sự kiện trong tháng -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-calendar-check text-warning fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success">+2</span>
                    </div>
                    <h6 class="text-muted mb-2 small text-uppercase">Sự kiện trong tháng</h6>
                    <h2 class="mb-0 fw-bold">8</h2>
                    <small class="text-muted d-block mt-2">↑ 2 sự kiện mới</small>
                </div>
            </div>
        </div>

        <!-- Điểm thưởng phát ra -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-star text-success fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success">↑ 15.4%</span>
                    </div>
                    <h6 class="text-muted mb-2 small text-uppercase">Điểm thưởng phát ra</h6>
                    <h2 class="mb-0 fw-bold">2,850</h2>
                    <small class="text-muted d-block mt-2">↑ 15.4% so với tháng trước</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Thống kê rác thải theo tháng -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Thống kê rác thải theo tháng
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="wasteChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Phân loại rác thải -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-chart-pie text-success me-2"></i>
                        Phân loại rác thải
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="wasteTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Xu hướng tham gia sinh viên -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-chart-area text-info me-2"></i>
                        Xu hướng tham gia sinh viên
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="studentTrendChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 5 sinh viên tích cực -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Top 5 sinh viên tích cực
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $topStudents = [
                            ['name' => 'Nguyễn Văn A', 'points' => 450, 'waste' => 89],
                            ['name' => 'Trần Thị B', 'points' => 420, 'waste' => 82],
                            ['name' => 'Lê Văn C', 'points' => 395, 'waste' => 78],
                            ['name' => 'Phạm Thị D', 'points' => 380, 'waste' => 75],
                            ['name' => 'Hoàng Văn E', 'points' => 365, 'waste' => 71],
                        ];
                    @endphp
                    <div class="list-group list-group-flush">
                        @foreach($topStudents as $index => $student)
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                                            {{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fw-semibold">{{ $student['name'] }}</h6>
                                        <small class="text-muted">{{ $student['points'] }} điểm • {{ $student['waste'] }} kg</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="progress" style="width: 80px; height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($student['points'] / 500) * 100 }}%" aria-valuenow="{{ $student['points'] }}" aria-valuemin="0" aria-valuemax="500"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart colors
    const primaryColor = '#0d6efd';
    const successColor = '#198754';
    const infoColor = '#0dcaf0';
    const warningColor = '#ffc107';
    const dangerColor = '#dc3545';

    // Thống kê rác thải theo tháng
    const wasteCtx = document.getElementById('wasteChart');
    if (wasteCtx) {
        new Chart(wasteCtx, {
            type: 'line',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
                datasets: [{
                    label: 'Rác thu gom (kg)',
                    data: [420, 480, 510, 490, 520, 534],
                    borderColor: primaryColor,
                    backgroundColor: primaryColor + '20',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Phân loại rác thải
    const wasteTypeCtx = document.getElementById('wasteTypeChart');
    if (wasteTypeCtx) {
        new Chart(wasteTypeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Rác tái chế', 'Rác hữu cơ', 'Rác vô cơ', 'Rác nguy hại'],
                datasets: [{
                    data: [35, 30, 25, 10],
                    backgroundColor: [primaryColor, successColor, infoColor, warningColor]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Xu hướng tham gia sinh viên
    const studentTrendCtx = document.getElementById('studentTrendChart');
    if (studentTrendCtx) {
        new Chart(studentTrendCtx, {
            type: 'bar',
            data: {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
                datasets: [{
                    label: 'Sinh viên tham gia',
                    data: [120, 135, 145, 150, 160, 167],
                    backgroundColor: infoColor,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .progress {
        border-radius: 10px;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
</style>
@endpush
@endsection
