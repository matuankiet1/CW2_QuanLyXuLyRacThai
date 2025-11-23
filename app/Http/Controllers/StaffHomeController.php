<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\CollectionSchedule;
use App\Models\Banner;
use App\Models\WasteType;
use App\Models\WasteLog;
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

     public function collection_schedules()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        $user_id = auth()->id();
        $wasteTypes = WasteType::pluck('name', 'id');
        $wasteLogs = WasteLog::paginate(7);
        $collectionSchedules = CollectionSchedule::where('staff_id', $user_id)->get();
        // dd( $collectionSchedules);
        $isSearch = false;
        return view('staff.collection-schedules.index', compact('wasteTypes', 'wasteLogs', 'collectionSchedules', 'isSearch'));
    }
}
