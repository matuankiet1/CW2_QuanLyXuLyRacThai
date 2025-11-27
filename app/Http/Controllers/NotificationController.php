<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use App\Services\IntegratedNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $users = User::orderBy('name')->get();
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
            'target_role' => 'nullable|required_if:send_to_type,role|in:admin,staff,student',
            'user_ids' => 'nullable|required_if:send_to_type,user|array',
            'user_ids.*' => 'exists:users,user_id',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            'scheduled_at' => 'nullable|date|after:now',
        ], [
            'scheduled_at.after' => 'Thời gian hẹn giờ phải trong tương lai.',
            'attachment.mimes' => 'File đính kèm phải là định dạng: pdf, doc, docx, jpg, jpeg, png.',
            'attachment.max' => 'File đính kèm không được vượt quá 10MB.',
        ]);

        // Upload attachment nếu có
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            try {
                $attachmentPath = $request->file('attachment')->store('notifications', 'public');
            } catch (\Exception $e) {
                Log::error('Failed to upload notification attachment', [
                    'error' => $e->getMessage(),
                    'user_id' => Auth::id(),
                ]);
                
                return back()->withInput()->with('error', 'Không thể upload file đính kèm. Vui lòng thử lại.');
            }
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
            // Gửi cho tất cả sinh viên
            $recipients = User::where('role', 'student')->get();
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

        // Nếu thông báo được gửi ngay (không phải scheduled), gửi qua IntegratedNotificationService
        if ($notification->status === 'sent' && $notification->sent_at && $recipients->isNotEmpty()) {
            // Gửi bất đồng bộ để không làm chậm response
            $this->sendViaIntegratedService($notification, $recipients);
            
            $message = 'Thông báo đã được gửi thành công đến ' . $recipients->count() . ' người nhận qua tất cả các kênh (in-app, email, push).';
        } elseif ($notification->status === 'scheduled') {
            $message = 'Thông báo đã được lên lịch gửi vào ' . $notification->scheduled_at->format('d/m/Y H:i') . '.';
        } else {
            $message = 'Thông báo đã được lưu thành công.';
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', $message);
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

        // Lấy danh sách notification_id trước khi update để cập nhật read_count
        $unreadNotificationUsers = NotificationUser::where('user_id', $user->user_id)
            ->whereNull('read_at')
            ->with('notification')
            ->get();

        // Update read_at cho tất cả thông báo chưa đọc
        NotificationUser::where('user_id', $user->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Cập nhật read_count cho mỗi notification
        $notificationIds = [];
        foreach ($unreadNotificationUsers as $notificationUser) {
            if ($notificationUser->notification) {
                $notificationIds[] = $notificationUser->notification_id;
            }
        }

        // Tăng read_count cho các notification (tránh duplicate)
        $uniqueNotificationIds = array_unique($notificationIds);
        foreach ($uniqueNotificationIds as $notificationId) {
            Notification::where('notification_id', $notificationId)->increment('read_count');
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

    /**
     * Gửi thông báo qua IntegratedNotificationService (email, push, in-app)
     *
     * @param Notification $notification
     * @param \Illuminate\Database\Eloquent\Collection $recipients
     * @return void
     */
    private function sendViaIntegratedService($notification, $recipients)
    {
        try {
            $recipientIds = $recipients->pluck('user_id')->toArray();
            
            // Gửi qua IntegratedNotificationService để có email và push
            $results = IntegratedNotificationService::sendToMany(
                $recipientIds,
                $notification->title,
                $notification->content
            );

            Log::info('Notification sent via IntegratedNotificationService', [
                'notification_id' => $notification->notification_id,
                'title' => $notification->title,
                'total_recipients' => $results['total'],
                'success_count' => $results['success'],
                'failed_count' => $results['failed'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send notification via IntegratedNotificationService', [
                'notification_id' => $notification->notification_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
