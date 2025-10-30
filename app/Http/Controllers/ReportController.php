<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\CollectionSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Hiển thị trang báo cáo tổng quan
     */
    public function index()
    {
        // Thống kê tổng quan
        $totalUsers = User::count();
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $totalSchedules = CollectionSchedule::count();
        
        // Thống kê người dùng theo tháng
        $userStats = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Thống kê bài viết theo tháng
        $postStats = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Top 5 người dùng tích cực nhất (dựa trên số bài viết)
        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();
        
        // Thống kê theo role
        $roleStats = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get();
        
        return view('admin.reports.index', compact(
            'totalUsers',
            'totalPosts', 
            'publishedPosts',
            'draftPosts',
            'totalSchedules',
            'userStats',
            'postStats',
            'topUsers',
            'roleStats'
        ));
    }
    
    /**
     * Báo cáo chi tiết người dùng
     */
    public function users(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = User::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($role) {
            $query->where('role', $role);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $users = $query->withCount(['posts' => function($q) {
            $q->where('status', 'published');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return view('admin.reports.users', compact('users', 'search', 'role', 'dateFrom', 'dateTo'));
    }
    
    /**
     * Báo cáo chi tiết bài viết
     */
    public function posts(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $category = $request->get('category');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = Post::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($category) {
            $query->where('category', $category);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        $posts = $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.reports.posts', compact('posts', 'search', 'status', 'category', 'dateFrom', 'dateTo'));
    }
    
    /**
     * Báo cáo lịch thu gom
     */
    public function schedules(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = CollectionSchedule::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($dateFrom) {
            $query->whereDate('collection_date', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('collection_date', '<=', $dateTo);
        }
        
        $schedules = $query->orderBy('collection_date', 'desc')
            ->paginate(15);
        
        return view('admin.reports.schedules', compact('schedules', 'search', 'status', 'dateFrom', 'dateTo'));
    }
    
    /**
     * Xuất báo cáo Excel
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'excel');
        
        // Logic xuất báo cáo sẽ được implement sau
        return response()->json(['message' => 'Chức năng xuất báo cáo đang được phát triển']);
    }
}