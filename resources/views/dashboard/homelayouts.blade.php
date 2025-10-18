@extends('layouts.app') {{-- Hoặc bỏ dòng này nếu bạn không có layout chính --}}

@section('content')
<div class="flex h-screen bg-gray-100">

    {{-- Sidebar --}}
    <aside class="flex-col w-64 bg-white border-r border-gray-200">
        {{-- Logo --}}
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-gray-900 font-bold">EcoSchool</h2>
                    <p class="text-xs text-gray-500">Quản lý rác thải</p>
                </div>
            </div>
        </div>

        {{-- Menu --}}
        @php
            $menuItems = [
                ['id'=>'home','label'=>'Trang chủ'],
                ['id'=>'dashboard','label'=>'Dashboard'],
                ['id'=>'users','label'=>'Quản lý người dùng'],
                ['id'=>'permissions','label'=>'Phân quyền'],
                ['id'=>'events','label'=>'Quản lý sự kiện'],
                ['id'=>'participants','label'=>'Quản lý sinh viên tham gia'],
                ['id'=>'reports','label'=>'Báo cáo người dùng'],
                ['id'=>'notifications','label'=>'Gửi thông báo'],
                ['id'=>'personal-stats','label'=>'Thống kê cá nhân'],
                ['id'=>'finance','label'=>'Quản lý tài chính'],
                ['id'=>'rewards','label'=>'Danh sách điểm thưởng'],
            ];
            $currentPage = 'dashboard'; // Hoặc truyền từ Controller
        @endphp

        <nav class="flex-1 p-4 overflow-y-auto">
            <ul class="space-y-1">
                @foreach ($menuItems as $item)
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors
                                {{ $currentPage == $item['id'] ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            {{-- Icon có thể thêm sau --}}
                            <span class="text-sm">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- User profile --}}
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                    A
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">Admin</p>
                    <p class="text-xs text-gray-500">admin@school.edu</p>
                </div>
                <button class="h-8 w-8 flex items-center justify-center rounded hover:bg-gray-200">
                    ⏻
                </button>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Header --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h1 class="text-lg font-bold text-gray-900">Dashboard</h1>
            <button class="px-3 py-1 border rounded hover:bg-gray-100">Thông báo</button>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6 space-y-6">
            {{-- Overview Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                            ['name'=>'Nguyễn Văn A','points'=>450,'waste'=>89],
                            ['name'=>'Trần Thị B','points'=>420,'waste'=>82],
                            ['name'=>'Lê Văn C','points'=>395,'waste'=>78],
                            ['name'=>'Phạm Thị D','points'=>380,'waste'=>75],
                            ['name'=>'Hoàng Văn E','points'=>365,'waste'=>71],
                        ];
                    @endphp
                    <div class="space-y-4">
                        @foreach($topStudents as $index => $student)
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm text-green-700">{{ $index+1 }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">{{ $student['name'] }}</p>
                                <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                                    <span>{{ $student['points'] }} điểm</span> • <span>{{ $student['waste'] }} kg</span>
                                </div>
                            </div>
                            <div class="w-32">
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 rounded-full" style="width: {{ ($student['points']/500)*100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </main>
    </div>

</div>

{{-- Include Chart.js --}}
@vite(['resources/js/dashboard.js'])

@endsection
