<?php

namespace App\Http\Controllers;

use App\Models\SimpleNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpleNotificationController extends Controller
{
    /**
     * Danh sách thông báo của user
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = SimpleNotification::forUser($user->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = SimpleNotification::forUser($user->user_id)
            ->unread()
            ->count();

        return view('user.simple-notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Chi tiết thông báo
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $notification = SimpleNotification::forUser($user->user_id)
            ->findOrFail($id);

        // Đánh dấu đã đọc nếu chưa đọc
        if ($notification->isUnread()) {
            $notification->markAsRead();
        }

        return view('user.simple-notifications.show', compact('notification'));
    }

    /**
     * Đánh dấu thông báo đã đọc
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $result = NotificationService::markAsRead($id, $user->user_id);

        if ($result) {
            return back()->with('success', 'Đã đánh dấu thông báo là đã đọc.');
        }

        return back()->with('error', 'Không tìm thấy thông báo hoặc đã được đánh dấu đọc.');
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $count = NotificationService::markAllAsRead($user->user_id);

        return back()->with('success', "Đã đánh dấu {$count} thông báo là đã đọc.");
    }
}

