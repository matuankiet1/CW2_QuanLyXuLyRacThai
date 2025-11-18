@extends('layouts.admin-with-sidebar')

@section('title', 'Dashboard - Admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-500 mt-1">Tổng quan hệ thống quản lý xử lý rác thải</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white">Bản thử nghiệm</span>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Tổng rác thu gom tháng này -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trash-alt text-green-600 text-xl"></i>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-600 text-xs font-semibold rounded-full">↑ 8.3%</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Tổng rác thu gom tháng này</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-1">534 <span class="text-lg text-gray-500">kg</span></h3>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 8.3% so với tháng trước</p>
                </div>
            </div>

            <!-- Sinh viên tham gia -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs font-semibold rounded-full">↑ 12.8%</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Sinh viên tham gia</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-1">167</h3>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 12.8% so với tháng trước</p>
                </div>
            </div>

            <!-- Sự kiện trong tháng -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow border-l-4 border-yellow-500">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                        </div>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-600 text-xs font-semibold rounded-full">+2</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Sự kiện trong tháng</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $upcomingEventsCount }}</h3>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 2 sự kiện mới</p>
                </div>
            </div>

            <!-- Điểm thưởng phát ra -->
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-purple-600 text-xl"></i>
                        </div>
                        <span class="px-2 py-1 bg-purple-100 text-purple-600 text-xs font-semibold rounded-full">↑ 15.4%</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Điểm thưởng phát ra</p>
                    <h3 class="text-3xl font-bold text-gray-900 mb-1">2,850</h3>
                    <p class="text-green-600 text-xs font-medium mt-2">↑ 15.4% so với tháng trước</p>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Thống kê rác thải theo tháng -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                        <h5 class="text-lg font-semibold text-gray-900">Thống kê rác thải theo tháng</h5>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative" style="height: 300px;">
                        <canvas id="wasteChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Phân loại rác thải -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-pie text-blue-600"></i>
                        </div>
                        <h5 class="text-lg font-semibold text-gray-900">Phân loại rác thải</h5>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative" style="height: 300px;">
                        <canvas id="wasteTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Xu hướng tham gia sinh viên -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-area text-purple-600"></i>
                        </div>
                        <h5 class="text-lg font-semibold text-gray-900">Xu hướng tham gia sinh viên</h5>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative" style="height: 300px;">
                        <canvas id="studentTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top 5 sinh viên tích cực -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-trophy text-yellow-600"></i>
                        </div>
                        <h5 class="text-lg font-semibold text-gray-900">Top 5 sinh viên tích cực</h5>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $topStudents = [
                            ['name' => 'Nguyễn Văn A', 'points' => 450, 'waste' => 89],
                            ['name' => 'Trần Thị B', 'points' => 420, 'waste' => 82],
                            ['name' => 'Lê Văn C', 'points' => 395, 'waste' => 78],
                            ['name' => 'Phạm Thị D', 'points' => 380, 'waste' => 75],
                            ['name' => 'Hoàng Văn E', 'points' => 365, 'waste' => 71],
                        ];
                    @endphp
                    <div class="space-y-4">
                        @foreach($topStudents as $index => $student)
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center font-bold text-sm shadow-md">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="font-semibold text-gray-900 truncate">{{ $student['name'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $student['points'] }} điểm • {{ $student['waste'] }} kg</div>
                                </div>
                                <div class="flex-shrink-0 w-24">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-500" style="width: {{ ($student['points'] / 500) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
    const primaryColor = '#10b981';
    const successColor = '#059669';
    const infoColor = '#34d399';
    const warningColor = '#f59e0b';
    const blueColor = '#3b82f6';
    const purpleColor = '#8b5cf6';

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
                    backgroundColor: primaryColor + '40',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: primaryColor,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            lineWidth: 1
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
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
                labels: ['Rác hữu cơ', 'Rác tái chế', 'Rác thải nguy hại', 'Rác thải khác'],
                datasets: [{
                    data: [35, 30, 25, 10],
                    backgroundColor: [
                        primaryColor,
                        successColor,
                        blueColor,
                        warningColor
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            },
                            color: '#374151',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
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
                    backgroundColor: purpleColor,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            lineWidth: 1
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            },
                            stepSize: 20
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection
