<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Controller: EventParticipantController
 * 
 * Mô tả: Quản lý sinh viên tham gia sự kiện (Admin)
 * - Xem danh sách sinh viên tham gia
 * - Xác nhận sinh viên
 * - Điểm danh sinh viên
 * - Xuất báo cáo
 */
class EventParticipantController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên tham gia sự kiện
     * Route: GET /admin/events/{id}/participants
     */
    public function index(Request $request, $id)
    {
        $event = Event::with(['createdBy'])->findOrFail($id);
        
        // Lọc theo trạng thái
        $status = $request->input('status', 'all');
        $query = EventUser::with(['user'])
                          ->where('event_id', $event->id);
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Tìm kiếm
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Sắp xếp
        $participants = $query->orderBy('registered_at', 'desc')
                             ->paginate(20);
        
        // Thống kê
        $stats = [
            'total' => EventUser::where('event_id', $event->id)->count(),
            'pending' => EventUser::where('event_id', $event->id)->where('status', 'pending')->count(),
            'confirmed' => EventUser::where('event_id', $event->id)->where('status', 'confirmed')->count(),
            'attended' => EventUser::where('event_id', $event->id)->where('status', 'attended')->count(),
            'canceled' => EventUser::where('event_id', $event->id)->where('status', 'canceled')->count(),
        ];
        
        return view('admin.events.participants', compact('event', 'participants', 'status', 'search', 'stats'));
    }

    /**
     * Xác nhận sinh viên tham gia
     * Route: PATCH /admin/events/{id}/participants/{userId}/confirm
     */
    public function confirm($id, $userId)
    {
        $event = Event::findOrFail($id);
        $registration = EventUser::where('event_id', $event->id)
                                ->where('user_id', $userId)
                                ->firstOrFail();
        
        // Kiểm tra trạng thái
        if ($registration->status !== 'pending') {
            return redirect()->back()
                            ->with('error', 'Chỉ có thể xác nhận đăng ký đang chờ xác nhận.');
        }
        
        try {
            $registration->confirm();
            
            return redirect()->back()
                            ->with('success', 'Xác nhận sinh viên tham gia thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Có lỗi xảy ra khi xác nhận. Vui lòng thử lại.');
        }
    }

    /**
     * Điểm danh sinh viên
     * Route: PATCH /admin/events/{id}/participants/{userId}/attend
     */
    public function attend($id, $userId)
    {
        $event = Event::findOrFail($id);
        $registration = EventUser::where('event_id', $event->id)
                                ->where('user_id', $userId)
                                ->firstOrFail();
        
        // Kiểm tra trạng thái (chỉ điểm danh khi đã xác nhận)
        if ($registration->status !== 'confirmed') {
            return redirect()->back()
                            ->with('error', 'Chỉ có thể điểm danh sinh viên đã được xác nhận.');
        }
        
        try {
            $registration->markAsAttended();
            
            return redirect()->back()
                            ->with('success', 'Điểm danh sinh viên thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Có lỗi xảy ra khi điểm danh. Vui lòng thử lại.');
        }
    }

    /**
     * Xác nhận hàng loạt
     * Route: POST /admin/events/{id}/participants/bulk-confirm
     */
    public function bulkConfirm(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,user_id',
        ]);
        
        $event = Event::findOrFail($id);
        $userIds = $request->input('user_ids');
        
        try {
            DB::beginTransaction();
            
            $count = EventUser::where('event_id', $event->id)
                             ->whereIn('user_id', $userIds)
                             ->where('status', 'pending')
                             ->update([
                                 'status' => 'confirmed',
                                 'confirmed_at' => now(),
                             ]);
            
            DB::commit();
            
            return redirect()->back()
                            ->with('success', "Đã xác nhận {$count} sinh viên thành công!");
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                            ->with('error', 'Có lỗi xảy ra khi xác nhận hàng loạt. Vui lòng thử lại.');
        }
    }

    /**
     * Điểm danh hàng loạt
     * Route: POST /admin/events/{id}/participants/bulk-attend
     */
    public function bulkAttend(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,user_id',
        ]);
        
        $event = Event::findOrFail($id);
        $userIds = $request->input('user_ids');
        
        try {
            DB::beginTransaction();
            
            $count = EventUser::where('event_id', $event->id)
                             ->whereIn('user_id', $userIds)
                             ->where('status', 'confirmed')
                             ->update([
                                 'status' => 'attended',
                                 'attended_at' => now(),
                             ]);
            
            DB::commit();
            
            return redirect()->back()
                            ->with('success', "Đã điểm danh {$count} sinh viên thành công!");
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                            ->with('error', 'Có lỗi xảy ra khi điểm danh hàng loạt. Vui lòng thử lại.');
        }
    }

    /**
     * Xuất báo cáo Excel
     * Route: GET /admin/events/{id}/participants/export
     */
    public function export($id)
    {
        $event = Event::findOrFail($id);
        $participants = EventUser::with(['user'])
                                ->where('event_id', $event->id)
                                ->whereIn('status', ['pending', 'confirmed', 'attended'])
                                ->orderBy('registered_at', 'desc')
                                ->get();
        
        // Tạo file Excel đơn giản (có thể sử dụng Maatwebsite\Excel nếu cần)
        $filename = 'event_' . $event->id . '_participants_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($participants, $event) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'STT',
                'Họ và tên',
                'Email',
                'Trạng thái',
                'Ngày đăng ký',
                'Ngày xác nhận',
                'Ngày điểm danh',
            ]);
            
            // Data
            $index = 1;
            foreach ($participants as $participant) {
                fputcsv($file, [
                    $index++,
                    $participant->user->name,
                    $participant->user->email,
                    $this->getStatusLabel($participant->status),
                    $participant->registered_at ? $participant->registered_at->format('d/m/Y H:i') : '',
                    $participant->confirmed_at ? $participant->confirmed_at->format('d/m/Y H:i') : '',
                    $participant->attended_at ? $participant->attended_at->format('d/m/Y H:i') : '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Lấy nhãn trạng thái
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'canceled' => 'Đã hủy',
            'attended' => 'Đã tham gia',
        ];
        
        return $labels[$status] ?? $status;
    }
}
