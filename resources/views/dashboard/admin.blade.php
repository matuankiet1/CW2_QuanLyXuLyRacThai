@extends('layouts.admin-with-sidebar')

@section('title', 'Dashboard - Admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Dashboard</h1>
            <span class="badge bg-success">Bản thử nghiệm</span>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-soft hover-lift h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Tổng rác thu gom tháng này</p>
                        <h3 class="mb-0">534 kg</h3>
                        <small class="text-success d-block mt-2">↑ 8.3% so với tháng trước</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-soft hover-lift h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Sinh viên tham gia</p>
                        <h3 class="mb-0">167</h3>
                        <small class="text-success d-block mt-2">↑ 12.8% so với tháng trước</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-soft hover-lift h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Sự kiện trong tháng</p>
                        <h3 class="mb-0">8</h3>
                        <small class="text-success d-block mt-2">↑ 2 sự kiện mới</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-soft hover-lift h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Điểm thưởng phát ra</p>
                        <h3 class="mb-0">2,850</h3>
                        <small class="text-success d-block mt-2">↑ 15.4% so với tháng trước</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12 col-lg-6">
                <div class="card shadow-soft">
                    <div class="card-body">
                        <h5 class="card-title">Thống kê rác thải theo tháng</h5>
                        <canvas id="wasteChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow-soft">
                    <div class="card-body">
                        <h5 class="card-title">Phân loại rác thải</h5>
                        <canvas id="wasteTypeChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12 col-lg-6">
                <div class="card shadow-soft">
                    <div class="card-body">
                        <h5 class="card-title">Xu hướng tham gia sinh viên</h5>
                        <canvas id="studentTrendChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card shadow-soft">
                    <div class="card-body">
                        <h5 class="card-title">Top 5 sinh viên tích cực</h5>
                @php
                    $topStudents = [
                        ['name' => 'Nguyễn Văn A', 'points' => 450, 'waste' => 89],
                        ['name' => 'Trần Thị B', 'points' => 420, 'waste' => 82],
                        ['name' => 'Lê Văn C', 'points' => 395, 'waste' => 78],
                        ['name' => 'Phạm Thị D', 'points' => 380, 'waste' => 75],
                        ['name' => 'Hoàng Văn E', 'points' => 365, 'waste' => 71],
                    ];
                @endphp
                        <div class="vstack gap-3">
                            @foreach($topStudents as $index => $student)
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;">
                                        <small>{{ $index + 1 }}</small>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium">{{ $student['name'] }}</div>
                                        <div class="text-muted small">{{ $student['points'] }} điểm • {{ $student['waste'] }} kg</div>
                                    </div>
                                    <div class="progress w-25" role="progressbar" aria-valuenow="{{ ($student['points'] / 500) * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar bg-success" style="width: {{ ($student['points'] / 500) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection