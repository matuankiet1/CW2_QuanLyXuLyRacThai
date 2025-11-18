<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller: EventRewardController
 * 
 * Mô tả: Quản lý danh sách sinh viên nhận điểm thưởng khi tham gia sự kiện (Admin)
 * - Xem danh sách sinh viên đã tham gia và nhận điểm thưởng
 * - Cập nhật điểm thưởng cho sinh viên
 * - Cập nhật điểm thưởng hàng loạt
 * - Xuất báo cáo danh sách sinh viên nhận điểm thưởng
 */
class EventRewardController extends Controller
{
    /**
     * Hiển thị danh sách sinh viên nhận điểm thưởng
     * Route: GET /admin/events/{id}/rewards
     */
    public function index(Request $request, $id)
    {
        $event = Event::with(['createdBy'])->findOrFail($id);
        
        // Chỉ lấy sinh viên đã tham gia (status = 'attended')
        $query = EventUser::with(['user'])
                          ->where('event_id', $event->id)
                          ->where('status', 'attended');
        
        // Tìm kiếm theo tên hoặc email
        $search = $request->input('search');
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Sắp xếp theo điểm thưởng (giảm dần) hoặc ngày điểm danh
        $sortBy = $request->input('sort_by', 'reward_points');
        $sortOrder = $request->input('sort_order', 'desc');
        
        $allowedSortColumns = ['reward_points', 'attended_at', 'name'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'reward_points';
        }
        
        if ($sortBy === 'name') {
            $query->join('users', 'event_user.user_id', '=', 'users.user_id')
                  ->orderBy('users.name', $sortOrder)
                  ->select('event_user.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $participants = $query->paginate(20);
        
        // Thống kê
        $stats = [
            'total' => EventUser::where('event_id', $event->id)->where('status', 'attended')->count(),
            'with_rewards' => EventUser::where('event_id', $event->id)
                                      ->where('status', 'attended')
                                      ->where('reward_points', '>', 0)
                                      ->count(),
            'total_points' => EventUser::where('event_id', $event->id)
                                      ->where('status', 'attended')
                                      ->sum('reward_points'),
        ];
        
        return view('admin.events.rewards', compact('event', 'participants', 'search', 'stats', 'sortBy', 'sortOrder'));
    }

    /**
     * Cập nhật điểm thưởng cho một sinh viên
     * Route: PATCH /admin/events/{id}/rewards/{userId}
     */
    public function update(Request $request, $id, $userId)
    {
        $request->validate([
            'reward_points' => 'required|integer|min:0|max:1000',
        ]);
        
        $event = Event::findOrFail($id);
        $registration = EventUser::where('event_id', $event->id)
                                ->where('user_id', $userId)
                                ->where('status', 'attended')
                                ->firstOrFail();
        
        try {
            $registration->update([
                'reward_points' => $request->input('reward_points'),
            ]);
            
            return redirect()->back()
                            ->with('success', 'Cập nhật điểm thưởng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Có lỗi xảy ra khi cập nhật điểm thưởng. Vui lòng thử lại.');
        }
    }

    /**
     * Cập nhật điểm thưởng hàng loạt
     * Route: POST /admin/events/{id}/rewards/bulk-update
     */
    public function bulkUpdate(Request $request, $id)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'required|exists:users,user_id',
            'reward_points' => 'required|integer|min:0|max:1000',
        ]);
        
        $event = Event::findOrFail($id);
        $userIds = $request->input('user_ids');
        $rewardPoints = $request->input('reward_points');
        
        try {
            DB::beginTransaction();
            
            $count = EventUser::where('event_id', $event->id)
                             ->whereIn('user_id', $userIds)
                             ->where('status', 'attended')
                             ->update([
                                 'reward_points' => $rewardPoints,
                             ]);
            
            DB::commit();
            
            return redirect()->back()
                            ->with('success', "Đã cập nhật điểm thưởng cho {$count} sinh viên thành công!");
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                            ->with('error', 'Có lỗi xảy ra khi cập nhật điểm thưởng hàng loạt. Vui lòng thử lại.');
        }
    }
}
