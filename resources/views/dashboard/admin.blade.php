@extends('layouts.admin-with-sidebar')

@section('title', 'Dashboard - Admin')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold mb-0">Dashboard</h1>
            <span class="px-2 py-1 rounded text-xs font-medium bg-green-500 text-white">Bản thử nghiệm</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow h-full">
                <div class="p-4">
                    <p class="text-gray-500 mb-1 text-sm">Tổng rác thu gom tháng này</p>
                    <h3 class="text-2xl font-semibold mb-0">534 kg</h3>
                    <small class="text-green-500 block mt-2">↑ 8.3% so với tháng trước</small>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow h-full">
                <div class="p-4">
                    <p class="text-gray-500 mb-1 text-sm">Sinh viên tham gia</p>
                    <h3 class="text-2xl font-semibold mb-0">167</h3>
                    <small class="text-green-500 block mt-2">↑ 12.8% so với tháng trước</small>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow h-full">
                <div class="p-4">
                    <p class="text-gray-500 mb-1 text-sm">Sự kiện trong tháng</p>
                    <h3 class="text-2xl font-semibold mb-0">8</h3>
                    <small class="text-green-500 block mt-2">↑ 2 sự kiện mới</small>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow h-full">
                <div class="p-4">
                    <p class="text-gray-500 mb-1 text-sm">Điểm thưởng phát ra</p>
                    <h3 class="text-2xl font-semibold mb-0">2,850</h3>
                    <small class="text-green-500 block mt-2">↑ 15.4% so với tháng trước</small>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4">
                    <h5 class="text-lg font-semibold mb-4">Thống kê rác thải theo tháng</h5>
                    <canvas id="wasteChart" height="300"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4">
                    <h5 class="text-lg font-semibold mb-4">Phân loại rác thải</h5>
                    <canvas id="wasteTypeChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4">
                    <h5 class="text-lg font-semibold mb-4">Xu hướng tham gia sinh viên</h5>
                    <canvas id="studentTrendChart" height="300"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4">
                    <h5 class="text-lg font-semibold mb-4">Top 5 sinh viên tích cực</h5>
                @php
                    $topStudents = [
                        ['name' => 'Nguyễn Văn A', 'points' => 450, 'waste' => 89],
                        ['name' => 'Trần Thị B', 'points' => 420, 'waste' => 82],
                        ['name' => 'Lê Văn C', 'points' => 395, 'waste' => 78],
                        ['name' => 'Phạm Thị D', 'points' => 380, 'waste' => 75],
                        ['name' => 'Hoàng Văn E', 'points' => 365, 'waste' => 71],
                    ];
                @endphp
                    <div class="flex flex-col gap-3">
                        @foreach($topStudents as $index => $student)
                            <div class="flex items-center gap-3">
                                <div class="rounded-full bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0" style="width:32px;height:32px;">
                                    <small class="text-xs">{{ $index + 1 }}</small>
                                </div>
                                <div class="flex-grow">
                                    <div class="font-medium">{{ $student['name'] }}</div>
                                    <div class="text-gray-500 text-sm">{{ $student['points'] }} điểm • {{ $student['waste'] }} kg</div>
                                </div>
                                <div class="w-1/4 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($student['points'] / 500) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart colors
    const primaryColor = '#10b981';
    const successColor = '#059669';
    const infoColor = '#34d399';
    const warningColor = '#f59e0b';

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
                maintainAspectRatio: false,
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
                maintainAspectRatio: false,
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
                maintainAspectRatio: false,
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
@endsection
