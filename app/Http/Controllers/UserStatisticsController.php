<?php

namespace App\Http\Controllers;

use App\Models\UserReport;
use App\Models\WasteLog;
use App\Models\CollectionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller: UserStatisticsController
 * 
 * Mô tả: Xử lý thống kê cá nhân cho user
 * - Lượng rác đã phân loại
 * - Số lần báo cáo
 */
class UserStatisticsController extends Controller
{
    /**
     * Hiển thị trang thống kê cá nhân
     * Route: GET /user/statistics
     */
    public function index()
    {
        $userId = Auth::user()->user_id;

        // 1. Đếm số lần báo cáo
        $totalReports = UserReport::where('user_id', $userId)->count();
        $pendingReports = UserReport::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
        $resolvedReports = UserReport::where('user_id', $userId)
            ->where('status', 'resolved')
            ->count();

        // 2. Tính lượng rác đã phân loại
        // Lấy các collection schedules mà user là staff
        $schedules = CollectionSchedule::where('staff_id', $userId)->pluck('schedule_id');
        
        // Khởi tạo giá trị mặc định
        $wasteLogsStats = (object)[
            'total_logs' => 0,
            'total_weight' => 0,
            'waste_types_count' => 0
        ];
        
        $wasteByType = collect();
        $monthlyStats = collect();
        
        // Chỉ tính toán nếu user có schedules
        if ($schedules->count() > 0) {
            // Đếm số lượng waste logs và tổng trọng lượng
            $wasteLogsStats = WasteLog::whereIn('schedule_id', $schedules)
                ->select(
                    DB::raw('COUNT(*) as total_logs'),
                    DB::raw('COALESCE(SUM(waste_weight), 0) as total_weight'),
                    DB::raw('COUNT(DISTINCT waste_type_id) as waste_types_count')
                )
                ->first() ?? $wasteLogsStats;

            // Thống kê theo loại rác
            $wasteByType = WasteLog::whereIn('schedule_id', $schedules)
                ->join('waste_types', 'waste_logs.waste_type_id', '=', 'waste_types.id')
                ->select(
                    'waste_types.name as waste_type_name',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('COALESCE(SUM(waste_logs.waste_weight), 0) as total_weight')
                )
                ->groupBy('waste_types.id', 'waste_types.name')
                ->orderByDesc('total_weight')
                ->get();

            // Thống kê theo tháng (6 tháng gần nhất)
            $monthlyStats = WasteLog::whereIn('schedule_id', $schedules)
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('COALESCE(SUM(waste_weight), 0) as total_weight')
                )
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return view('user.statistics.index', compact(
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'wasteLogsStats',
            'wasteByType',
            'monthlyStats'
        ));
    }
}

