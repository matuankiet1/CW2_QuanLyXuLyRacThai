<?php

namespace App\Http\Controllers;

use App\Models\SimpleNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SimpleNotificationController extends Controller
{
    /**
     * Danh sách thông báo của user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = SimpleNotification::forUser($user->user_id);

        // Filter theo trạng thái đọc/chưa đọc
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        // Search theo title hoặc message
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'title', 'is_read'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $notifications = $query->paginate(15)->withQueryString();

        // Tối ưu: Tính toán stats trong một query duy nhất
        $statsQuery = SimpleNotification::forUser($user->user_id);
        $totalCount = (clone $statsQuery)->count();
        $unreadCount = (clone $statsQuery)->unread()->count();
        $readCount = $totalCount - $unreadCount;

        $stats = [
            'total' => $totalCount,
            'unread' => $unreadCount,
            'read' => $readCount,
        ];

        return view('user.simple-notifications.index', compact('notifications', 'unreadCount', 'stats'));
    }

    /**
     * Chi tiết thông báo
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            
            $notification = SimpleNotification::forUser($user->user_id)
                ->findOrFail($id);

            // Đánh dấu đã đọc nếu chưa đọc
            if ($notification->isUnread()) {
                $notification->markAsRead();
            }

            return view('user.simple-notifications.show', compact('notification'));
        } catch (\Exception $e) {
            Log::error('SimpleNotificationController@show: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'notification_id' => $id,
            ]);
            
            return redirect()->route('user.simple-notifications.index')
                ->with('error', 'Không tìm thấy thông báo.');
        }
    }

    /**
     * Đánh dấu thông báo đã đọc
     */
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            $result = NotificationService::markAsRead($id, $user->user_id);

            if ($result) {
                if (request()->ajax() || request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã đánh dấu thông báo là đã đọc.'
                    ]);
                }
                
                return back()->with('success', 'Đã đánh dấu thông báo là đã đọc.');
            }

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông báo hoặc đã được đánh dấu đọc.'
                ], 404);
            }

            return back()->with('error', 'Không tìm thấy thông báo hoặc đã được đánh dấu đọc.');
        } catch (\Exception $e) {
            Log::error('SimpleNotificationController@markAsRead: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'notification_id' => $id,
            ]);

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi đánh dấu thông báo.'
                ], 500);
            }

            return back()->with('error', 'Có lỗi xảy ra khi đánh dấu thông báo.');
        }
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            $count = NotificationService::markAllAsRead($user->user_id);

            if ($count > 0) {
                return back()->with('success', "Đã đánh dấu {$count} thông báo là đã đọc.");
            }

            return back()->with('info', 'Không có thông báo nào cần đánh dấu đọc.');
        } catch (\Exception $e) {
            Log::error('SimpleNotificationController@markAllAsRead: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi đánh dấu tất cả thông báo.');
        }
    }
}
