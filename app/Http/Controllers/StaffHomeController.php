<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Event;
use App\Models\UserReport;
use Illuminate\Support\Facades\Auth;
use App\Models\EventUser;
use App\Models\CollectionSchedule;
use App\Models\Banner;
use App\Models\WasteType;
use App\Models\WasteLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffHomeController extends Controller
{
    public function index()
    {
        // Lấy bài viết mới nhất
        $latestPosts = Post::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Lấy lịch thu gom sắp tới
        $upcomingSchedules = CollectionSchedule::where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date', 'asc')
            ->limit(3)
            ->get();

        // Lấy banner mới nhất
        $banners = Banner::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Thống kê nhanh
        $stats = [
            'total_posts' => Post::where('status', 'published')->count(),
            'total_schedules' => CollectionSchedule::count(),
            'upcoming_schedules' => CollectionSchedule::where('scheduled_date', '>=', now())->count(),
        ];

        return view('staff.home.index', compact('latestPosts', 'upcomingSchedules', 'banners', 'stats'));
    }

    public function about()
    {
        return view('staff.home.about');
    }

    public function contact()
    {
        return view('staff.home.contact');
    }

    public function collection_schedule(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wasteTypes = WasteType::pluck('name', 'id');
        $wasteLogs = WasteLog::paginate(7);

        $search = $request->input('search');
        $sort = $request->input('sort'); // Lấy giá trị dropdown sắp xếp
        $statusFilter = $request->input('status');



        // Bắt đầu query builder
        $collectionSchedules = CollectionSchedule::with('staff');

        if ($statusFilter && $statusFilter !== 'all') {
            $collectionSchedules = $collectionSchedules->where('status', $statusFilter);
        }

        // Tìm kiếm
        if ($search) {
            $collectionSchedules = $collectionSchedules->where(function ($query) use ($search) {
                $query->where('schedule_id', 'like', "%$search%")
                    ->orWhereHas('staff', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
            $isSearching = true;
        } else {
            $isSearching = false;
        }

        // Sắp xếp
        switch ($sort) {
            case 'id_asc':
                $collectionSchedules = $collectionSchedules->orderBy('schedule_id', 'asc');
                break;
            case 'id_desc':
                $collectionSchedules = $collectionSchedules->orderBy('schedule_id', 'desc');
                break;
            case 'date_asc':
                $collectionSchedules = $collectionSchedules->orderBy('scheduled_date', 'asc');
                break;
            case 'date_desc':
                $collectionSchedules = $collectionSchedules->orderBy('scheduled_date', 'desc');
                break;
            default:
                $collectionSchedules = $collectionSchedules->orderBy('schedule_id', 'asc');
        }

        // Lấy kết quả
        $collectionSchedules = $collectionSchedules->paginate(10)->appends(request()->query());

        return view('staff.collection_schedules.index', compact(
            'wasteTypes',
            'wasteLogs',
            'collectionSchedules',
            'isSearching',
            'search',
            'sort'
        ));
    }

   


    public function postHome()
    {
        // Lấy danh sách bài viết đã publish (trạng thái = published)
        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6); // chia trang 6 bài mỗi trang

        return view('staff.posts.home', compact('posts'));
    }
    public function postShow($slug)
    {
        // Lấy bài viết theo slug
        $post = Post::where('slug', $slug)->firstOrFail();

        // Tăng lượt xem
        $post->increment('post_views');

        return view('staff.posts.show', compact('post'));
    }

    /**
     * Hiển thị danh sách sự kiện (cho nhân viên)
     * Route: GET /events
     */
    public function eventHome(Request $request)
    {
        $query = Event::query();

        $status = $request->input('status', 'all');
        $today = now()->toDateString();

        // Filter theo status động dựa trên ngày
        if ($status && $status !== 'all') {
            $query->where(function ($q) use ($status, $today) {
                switch ($status) {
                    case 'ended':
                        $q->whereDate('event_end_date', '<', $today);
                        break;
                    case 'on_going':
                        $q->whereDate('event_start_date', '<=', $today)
                            ->whereDate('event_end_date', '>=', $today);
                        break;
                    case 'registering':
                        $q->whereDate('register_date', '<=', $today)
                            ->whereDate('register_end_date', '>=', $today);
                        break;
                    case 'register_ended':
                        $q->whereDate('register_end_date', '<', $today)
                            ->whereDate('event_start_date', '>', $today);
                        break;
                    case 'up_coming':
                        $q->whereDate('register_date', '>', $today);
                        break;
                }
            });
        }

        // Tìm kiếm
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sắp xếp và phân trang
        $events = $query->orderBy('event_start_date', 'asc')->paginate(12);

        // Lấy trạng thái đăng ký của user hiện tại
        $userRegistrations = [];
        if (Auth::check()) {
            $userRegistrations = EventUser::where('user_id', Auth::id())
                ->whereIn('event_id', $events->pluck('id'))
                ->pluck('status', 'event_id')
                ->toArray();
        }

        return view('staff.events.index', compact('events', 'status', 'search', 'userRegistrations'));
    }

    public function eventShow($id)
    {
        $event = Event::with(['createdBy', 'registrations.user'])
            ->findOrFail($id);

        // Kiểm tra sinh viên đã đăng ký chưa
        $userRegistration = null;
        $isRegistered = false;

        if (Auth::check()) {
            $userRegistration = EventUser::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->first();
            $isRegistered = $event->isRegisteredBy(Auth::id());
        }

        // Đếm số lượng đăng ký theo trạng thái
        $registrationStats = [
            'pending' => $event->getRegistrationCountByStatus('pending'),
            'confirmed' => $event->getRegistrationCountByStatus('confirmed'),
            'attended' => $event->getRegistrationCountByStatus('attended'),
            'total' => $event->registrations()->count(),
        ];

        return view('staff.events.show', compact('event', 'userRegistration', 'isRegistered', 'registrationStats'));
    }

    public function wasteLog(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id(); // nhân viên hiện tại

        $wasteTypes = WasteType::pluck('name', 'id');

        $search = $request->input('search');
        $sort = $request->input('sort');
        $statusFilter = $request->input('status');

        // Lấy lịch thu gom của nhân viên hiện tại
        $collectionSchedules = CollectionSchedule::with('staff')
            ->where('staff_id', $userId);

        // Lọc theo trạng thái nếu có
        if ($statusFilter && $statusFilter !== 'all') {
            $collectionSchedules = $collectionSchedules->where('status', $statusFilter);
        }

        // Tìm kiếm
        if ($search) {
            $collectionSchedules = $collectionSchedules->where(function ($query) use ($search) {
                $query->where('schedule_id', 'like', "%$search%")
                    ->orWhereHas('staff', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        // Sắp xếp
        switch ($sort) {
            case 'id_asc':
                $collectionSchedules = $collectionSchedules->orderBy('schedule_id', 'asc');
                break;
            case 'id_desc':
                $collectionSchedules = $collectionSchedules->orderBy('schedule_id', 'desc');
                break;
            case 'date_asc':
                $collectionSchedules = $collectionSchedules->orderBy('scheduled_date', 'asc');
                break;
            case 'date_desc':
                $collectionSchedules = $collectionSchedules->orderBy('scheduled_date', 'desc');
                break;
            default:
                $collectionSchedules = $collectionSchedules->orderBy('schedule_id', 'asc');
        }

        // Phân trang 7 lịch mỗi trang
        $collectionSchedules = $collectionSchedules->paginate(7)->withQueryString();

        return view('staff.waste-logs.index', compact(
            'wasteTypes',
            'collectionSchedules',
            'search',
            'sort',
            'statusFilter'
        ));
    }

    public function statistic()
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
        $wasteLogsStats = (object) [
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

        return view('staff.statistics.index', compact(
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'wasteLogsStats',
            'wasteByType',
            'monthlyStats'
        ));
    }

    public function createReport()
    {
        return view('staff.reports.create');
    }
}
