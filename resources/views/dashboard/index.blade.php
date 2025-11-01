@extends('layouts.dashboard')

@section('main-content')
    <div class="p-6">
        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="p-6 border-l-4 border-green-500 bg-white rounded-lg shadow">
                <p class="text-sm text-gray-500 mb-1">Tổng rác thu gom tháng này</p>
                <h3 class="text-2xl font-semibold text-gray-900">534 kg</h3>
                <p class="text-xs text-green-600 mt-2">↑ 8.3% so với tháng trước</p>
            </div>
            <div class="p-6 border-l-4 border-emerald-500 bg-white rounded-lg shadow">
                <p class="text-sm text-gray-500 mb-1">Sinh viên tham gia</p>
                <h3 class="text-2xl font-semibold text-gray-900">167</h3>
                <p class="text-xs text-green-600 mt-2">↑ 12.8% so với tháng trước</p>
            </div>
            <div class="p-6 border-l-4 border-teal-500 bg-white rounded-lg shadow">
                <p class="text-sm text-gray-500 mb-1">Sự kiện trong tháng</p>
                <h3 class="text-2xl font-semibold text-gray-900">8</h3>
                <p class="text-xs text-green-600 mt-2">↑ 2 sự kiện mới</p>
            </div>
            <div class="p-6 border-l-4 border-cyan-500 bg-white rounded-lg shadow">
                <p class="text-sm text-gray-500 mb-1">Điểm thưởng phát ra</p>
                <h3 class="text-2xl font-semibold text-gray-900">2,850</h3>
                <p class="text-xs text-green-600 mt-2">↑ 15.4% so với tháng trước</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-4 font-semibold text-gray-900">Thống kê rác thải theo tháng</h3>
                <canvas id="wasteChart" height="300"></canvas>
            </div>
            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-4 font-semibold text-gray-900">Phân loại rác thải</h3>
                <canvas id="wasteTypeChart" height="300"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-4 font-semibold text-gray-900">Xu hướng tham gia sinh viên</h3>
                <canvas id="studentTrendChart" height="300"></canvas>
            </div>

            {{-- Top students --}}
            <div class="p-6 bg-white rounded-lg shadow">
                <h3 class="mb-4 font-semibold text-gray-900">Top 5 sinh viên tích cực</h3>
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
                            <div
                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm text-green-700">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">{{ $student['name'] }}</p>
                                <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                                    <span>{{ $student['points'] }} điểm</span> • <span>{{ $student['waste'] }} kg</span>
                                </div>
                            </div>
                            <div class="w-32">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full"
                                        style="width: {{ ($student['points'] / 500) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Include Chart.js --}}
    @vite(['resources/js/dashboard.js'])
@endsection

