<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    /**
     * Danh sách thông báo đã gửi (Admin/Giảng viên)
     */
    public function index()
    {
        $notifications = Notification::with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Notification::count(),
            'sent' => Notification::where('status', 'sent')->count(),
            'scheduled' => Notification::where('status', 'scheduled')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Form tạo thông báo mới (Admin/Giảng viên)
     */
    public function create()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.notifications.create', compact('users'));
    }

    /**
     * Lưu thông báo mới (Admin/Giảng viên)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:announcement,academic,event,urgent',
            'send_to_type' => 'required|in:all,role,user',
            'target_role' => 'nullable|required_if:send_to_type,role|in:admin,user',
            'user_ids' => 'nullable|required_if:send_to_type,user|array',
            'user_ids.*' => 'exists:users,user_id',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'scheduled_at' => 'nullable|date',
        ]);

        // Upload attachment nếu có
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('notifications', 'public');
        }

        // Tạo thông báo
        $notification = Notification::create([
            'sender_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'attachment' => $attachmentPath,
            'send_to_type' => $validated['send_to_type'],
            'target_role' => $validated['target_role'] ?? null,
            'status' => $request->filled('scheduled_at') ? 'scheduled' : 'sent',
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'sent_at' => $request->filled('scheduled_at') ? null : now(),
        ]);

        // Xác định danh sách người nhận
        $recipients = collect();

        if ($validated['send_to_type'] === 'all') {
            // Gửi cho tất cả user
            $recipients = User::where('role', 'user')->get();
        } elseif ($validated['send_to_type'] === 'role') {
            // Gửi theo role
            $recipients = User::where('role', $validated['target_role'])->get();
        } elseif ($validated['send_to_type'] === 'user') {
            // Gửi cho user cụ thể
            $recipients = User::whereIn('user_id', $validated['user_ids'])->get();
        }

        // Lưu danh sách người nhận
        $notification->recipients()->attach($recipients->pluck('user_id')->toArray());

        // Cập nhật số lượng người nhận
        $notification->update([
            'total_recipients' => $recipients->count(),
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Thông báo đã được gửi thành công đến ' . $recipients->count() . ' người nhận.');
    }

    /**
     * Chi tiết thông báo (Admin/Giảng viên)
     */
    public function show($id)
    {
        $notification = Notification::with(['sender', 'recipients' => function($query) {
            $query->orderBy('pivot_read_at', 'desc');
        }])->findOrFail($id);

        $stats = [
            'total_recipients' => $notification->total_recipients,
            'read_count' => $notification->read_count,
            'unread_count' => $notification->total_recipients - $notification->read_count,
            'read_percentage' => $notification->getReadPercentage(),
        ];

        return view('admin.notifications.show', compact('notification', 'stats'));
    }

    /**
     * Danh sách thông báo của user (Sinh viên)
     */
    public function userIndex()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->with('sender')
            ->orderBy('notifications.created_at', 'desc')
            ->paginate(15);

        // Đếm số thông báo chưa đọc
        $unreadCount = NotificationUser::where('user_id', $user->user_id)
            ->whereNull('read_at')
            ->count();

        return view('user.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Chi tiết thông báo của user (Sinh viên)
     */
    public function userShow($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->with('sender')->findOrFail($id);

        // Đánh dấu đã đọc
        $pivotRecord = NotificationUser::where('notification_id', $notification->notification_id)
            ->where('user_id', $user->user_id)
            ->first();

        if ($pivotRecord && !$pivotRecord->isRead()) {
            $pivotRecord->markAsRead();

            // Cập nhật read_count trong notifications
            $notification->increment('read_count');
        }

        return view('user.notifications.show', compact('notification'));
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        NotificationUser::where('user_id', $user->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Cập nhật read_count cho mỗi notification
        $unreadNotifications = NotificationUser::where('user_id', $user->user_id)
            ->whereNull('read_at')
            ->with('notification')
            ->get();

        foreach ($unreadNotifications as $notificationUser) {
            $notificationUser->notification->increment('read_count');
        }

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }

    /**
     * Xóa thông báo (Admin/Giảng viên - chỉ xóa khi chưa gửi)
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        // Chỉ cho phép xóa nếu là draft hoặc scheduled (chưa gửi)
        if ($notification->status === 'sent') {
            return back()->with('error', 'Không thể xóa thông báo đã gửi.');
        }

        // Xóa file đính kèm nếu có
        if ($notification->attachment) {
            Storage::disk('public')->delete($notification->attachment);
        }

        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Thông báo đã được xóa thành công.');
    }

    /**
     * Tải file đính kèm
     */
    public function downloadAttachment($id)
    {
        $notification = Notification::findOrFail($id);

        if (!$notification->attachment) {
            abort(404, 'File đính kèm không tồn tại.');
        }

        return Storage::disk('public')->download($notification->attachment);
    }
}
