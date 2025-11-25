<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\CollectionSchedule;
use App\Models\Banner;
use App\Models\WasteType;
use App\Models\WasteLog;
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
        $upcomingSchedules = CollectionSchedule::where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date', 'asc')
            ->limit(3)
            ->get();

        // Lấy banner mới nhất

    $stats = [
            'total_posts' => Post::count(),
            'total_schedules' => CollectionSchedule::count(), // Sửa thành CollectionSchedule
            'upcoming_schedules' => CollectionSchedule::where('scheduled_date', '>=', now())->count(), // Sửa thành CollectionSchedule
        ];

        $latestPosts = Post::where('status', 'published')
                          ->latest()
                          ->take(6)
                          ->get();

        $upcomingSchedules = CollectionSchedule::where('scheduled_date', '>=', now()) // Sửa thành CollectionSchedule
                                              ->orderBy('scheduled_date')
                                              ->take(3)
                                              ->get();

        // Phân loại banner theo vị trí
        $topBanners = Banner::where('position', 'top')
                            ->where('status', 1)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $sidebarBanners = Banner::where('position', 'sidebar')
                               ->where('status', 1)
                               ->orderBy('created_at', 'desc')
                               ->get();

        $footerBanners = Banner::where('position', 'footer')
                              ->where('status', 1)
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('home.index', compact(
            'stats', 
            'latestPosts', 
            'upcomingSchedules',
            'topBanners',
            'sidebarBanners', 
            'footerBanners'
        ));

    }

     public function collection_schedules(Request $request)
    {
       if (!auth()->check()) {
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

        return view('user.collection_schedules.index', compact(
            'wasteTypes',
            'wasteLogs',
            'collectionSchedules',
            'isSearching',
            'search',
            'sort'
        ));
    }

    public function about()
    {
        return view('home.about');
    }

    public function contact()
    {
        return view('home.contact');
    }

     public function wasteSortingGuide(){
        return view('home.sorting_guide');
    }
}
