@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Báo cáo tổng quan hệ thống
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Thống kê tổng quan -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalUsers }}</h3>
                                    <p>Tổng người dùng</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="{{ route('admin.reports.users') }}" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $totalPosts }}</h3>
                                    <p>Tổng bài viết</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <a href="{{ route('admin.reports.posts') }}" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $publishedPosts }}</h3>
                                    <p>Bài viết đã xuất bản</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $totalSchedules }}</h3>
                                    <p>Lịch thu gom</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <a href="{{ route('admin.reports.schedules') }}" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Biểu đồ thống kê -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Thống kê người dùng theo tháng</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="userChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Thống kê bài viết theo tháng</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="postChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bảng top người dùng -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Top 5 người dùng tích cực</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên</th>
                                                    <th>Email</th>
                                                    <th>Số bài viết</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topUsers as $index => $user)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="badge badge-primary">{{ $user->posts_count }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Phân bố theo vai trò</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="roleChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ người dùng theo tháng
const userCtx = document.getElementById('userChart').getContext('2d');
const userChart = new Chart(userCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($userStats->pluck('month')) !!},
        datasets: [{
            label: 'Số người dùng mới',
            data: {!! json_encode($userStats->pluck('count')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Biểu đồ bài viết theo tháng
const postCtx = document.getElementById('postChart').getContext('2d');
const postChart = new Chart(postCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($postStats->pluck('month')) !!},
        datasets: [{
            label: 'Số bài viết mới',
            data: {!! json_encode($postStats->pluck('count')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Biểu đồ phân bố vai trò
const roleCtx = document.getElementById('roleChart').getContext('2d');
const roleChart = new Chart(roleCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($roleStats->pluck('role')) !!},
        datasets: [{
            data: {!! json_encode($roleStats->pluck('count')) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true
    }
});
</script>
@endpush
@endsection
