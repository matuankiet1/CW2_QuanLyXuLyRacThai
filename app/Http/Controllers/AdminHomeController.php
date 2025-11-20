<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\CollectionSchedule;
use App\Models\Banner;
use App\Models\User;
use Carbon\Carbon;

class AdminHomeController extends Controller
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
        $topBanners = Banner::where('position', 'top')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $sidebarBanners = Banner::where('position', 'sidebar')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $footerBanners = Banner::where('position', 'footer')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Thống kê nhanh cho admin
        $stats = [
            'total_posts' => Post::where('status', 'published')->count(),
            'total_schedules' => CollectionSchedule::count(),
            'upcoming_schedules' => CollectionSchedule::where('scheduled_date', '>=', now())->count(),
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'regular_users' => User::where('role', 'student')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        return view('admin.home.index', compact('latestPosts', 'upcomingSchedules', 'topBanners', 'sidebarBanners', 'footerBanners', 'stats'));
    }

    public function about()
    {
        return view('admin.home.about');
    }

    public function contact()
    {
        return view('admin.home.contact');
    }
}
