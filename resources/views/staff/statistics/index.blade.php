@extends('layouts.staff')

@section('title', 'Thống kê cá nhân')

@section('content')
<div class="container mx-auto px-4 py-8 mt-20">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-chart-line mr-3 text-green-600"></i>
                Thống kê cá nhân
            </h1>
            <p class="text-gray-600">Xem thống kê về lượng rác đã phân loại và số lần báo cáo của bạn</p>
        </div>

        {{-- Cards thống kê tổng quan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Card: Tổng số báo cáo --}}
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Tổng số báo cáo</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalReports }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-flag text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Card: Báo cáo đang xử lý --}}
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Đang xử lý</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $pendingReports }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-4">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Card: Báo cáo đã giải quyết --}}
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Đã giải quyết</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $resolvedReports }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Card: Tổng lượng rác đã phân loại --}}
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Lượng rác đã phân loại</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ number_format($wasteLogsStats->total_weight ?? 0, 2) }} kg
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $wasteLogsStats->total_logs ?? 0 }} lần phân loại
                        </p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <i class="fas fa-recycle text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thống kê chi tiết --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Thống kê theo loại rác --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                    Thống kê theo loại rác
                </h2>
                @if($wasteByType->count() > 0)
                    <div class="space-y-4">
                        @foreach($wasteByType as $waste)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-trash-alt text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $waste->waste_type_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $waste->count }} lần phân loại</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">
                                        {{ number_format($waste->total_weight, 2) }} kg
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Chưa có dữ liệu phân loại rác</p>
                    </div>
                @endif
            </div>

            {{-- Thống kê theo tháng --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                    Thống kê theo tháng (6 tháng gần nhất)
                </h2>
                @if($monthlyStats->count() > 0)
                    <div class="space-y-4">
                        @foreach($monthlyStats as $month)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('m/Y') }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $month->count }} lần phân loại</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-blue-600">
                                        {{ number_format($month->total_weight, 2) }} kg
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Chưa có dữ liệu trong 6 tháng gần nhất</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Nút quay lại --}}
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại trang chủ
            </a>
        </div>
    </div>
</div>
@endsection

