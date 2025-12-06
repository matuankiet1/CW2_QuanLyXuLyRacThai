<?php

namespace App\Http\Controllers;

use App\Models\CollectionSchedule;
use App\Models\Event;
use App\Models\EventUser;
use App\Models\WasteLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        $upcomingEventsCount = Event::whereMonth('register_date', now()->month)->count();

        $totalStudents = EventUser::where('status', 'attended')
            ->distinct('user_id')
            ->count('user_id');

        $totalWastes = WasteLog::sum('waste_weight');

        // Nếu user không chọn năm -> lấy năm hiện tại
        $year = $request['year'];
        if (!$year) {
            $year = now()->year;
        }

        $wasteStatistics = $this->getWasteStatistics($request, $year);

        $wasteClassification = $this->getWasteClassification();

        // Nếu là request AJAX (JS fetch) -> trả JSON
        if ($request->ajax()) {
            return response()->json($wasteStatistics);
        }

        return view('dashboard.admin', compact('upcomingEventsCount', 'wasteStatistics', 'wasteClassification', 'totalStudents', 'totalWastes'));
    }

    // Manager method đã bị xóa vì role manager không còn tồn tại
    // Các chức năng của manager đã được chuyển sang admin

    public function staff()
    {
        $user = auth()->user();

        $collectionSchedules = CollectionSchedule::with('staff')
            ->where('staff_id', $user->user_id)
            ->orderBy('scheduled_date', 'asc')
            ->get();

        $isSearching = false;

        return view('dashboard.staff', compact('collectionSchedules', 'isSearching'));
    }

    public function student()
    {
        $now = now();

        $registeringEvents = Event::where('register_date', '<', $now)
            ->where('register_end_date', '>', $now)
            ->get();

        $registeringEventsCount = $registeringEvents->count();
        return view('dashboard.student', compact('registeringEvents', 'registeringEventsCount'));
    }


    // Thống kê rác thải theo tháng dựa vào năm mà người dùng chọn
    public function getWasteStatistics(Request $request, $y)
    {
        $year = $request['year'];
        if (!$year) {
            $year = $y;
        }
        // Lấy tổng kg theo từng tháng trong năm được chọn
        $rawStats = DB::table('waste_logs')
            ->join('collection_schedules', 'waste_logs.schedule_id', '=', 'collection_schedules.schedule_id')
            ->selectRaw('MONTH(collection_schedules.scheduled_date) as month, SUM(waste_logs.waste_weight) as total_weight')
            ->whereYear('collection_schedules.scheduled_date', $year)
            ->groupByRaw('MONTH(collection_schedules.scheduled_date)')
            ->pluck('total_weight', 'month');

        // Chuẩn hoá 12 tháng
        $labels = [];
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = "Tháng $m";
            $data[] = isset($rawStats[$m]) ? (float) $rawStats[$m] : 0;
        }

        // Danh sách năm cho dropdown (lấy từ DB)
        $years = DB::table('collection_schedules')
            ->selectRaw('DISTINCT YEAR(scheduled_date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $wasteStatistics = [
            'data' => $data,
            'labels' => $labels,
            'year' => $year,
            'years' => $years
        ];

        return $wasteStatistics;
    }

    // Phân loại rác thải
    public function getWasteClassification()
    {
        $wasteStats = DB::table('waste_types')
            ->leftJoin('waste_logs', 'waste_logs.waste_type_id', '=', 'waste_types.id')
            ->select(
                'waste_types.name',
                DB::raw('COALESCE(SUM(waste_logs.waste_weight), 0) AS total_weight')
            )
            ->groupBy('waste_types.id', 'waste_types.name')
            ->get();

        $total = $wasteStats->sum('total_weight');

        $labels = [];
        $percentages = [];

        foreach ($wasteStats as $item) {
            $labels[] = $item->name;

            if ($total > 0) {
                $percentages[] = round(($item->total_weight / $total) * 100, 2); // làm tròn 2 số
            } else {
                $percentages[] = 0;
            }
        }

        $wasteClassification = [
            'labels' => $labels,
            'weights' => $percentages
        ];

        return $wasteClassification;
    }



}
