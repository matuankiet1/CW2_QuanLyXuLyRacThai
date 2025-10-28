<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\CollectionSchedule;
use App\Models\Banner;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy bài viết mới nhất
        $latestPosts = Post::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Lấy lịch thu gom sắp tới
        $upcomingSchedules = CollectionSchedule::where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->limit(3)
            ->get();

        // Lấy banner hoạt động
        $banners = Banner::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Thống kê nhanh
        $stats = [
            'total_posts' => Post::where('status', 'published')->count(),
            'total_schedules' => CollectionSchedule::count(),
            'upcoming_schedules' => CollectionSchedule::where('date', '>=', now())->count(),
        ];

        return view('home.index', compact('latestPosts', 'upcomingSchedules', 'banners', 'stats'));
    }

    public function about()
    {
        return view('home.about');
    }

    public function contact()
    {
        return view('home.contact');
    }
}
