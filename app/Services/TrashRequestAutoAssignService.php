<?php

namespace App\Services;

use App\Models\User;
use App\Models\TrashRequest;
use App\Services\TrashRequestStateMachine;

/**
 * Service tự động gán admin cho yêu cầu thu gom rác
 * 
 * Logic: Gán cho admin có ít nhiệm vụ nhất
 * Tất cả admin đều có thể xem và xử lý yêu cầu
 * Nhiệm vụ được tính dựa trên số lượng requests có status != admin_approved
 */
class TrashRequestAutoAssignService
{
    /**
     * Tự động chọn và gán admin cho yêu cầu thu gom rác
     * 
     * @param TrashRequest $trashRequest
     * @return User|null Admin được gán, hoặc null nếu không có admin nào
     */
    public function autoAssign(TrashRequest $trashRequest): ?User
    {
        // Lấy tất cả admin
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            return null;
        }

        // Tính số lượng nhiệm vụ đang có của mỗi admin
        // Nhiệm vụ = số requests có status != admin_approved
        $adminWorkloads = [];
        foreach ($admins as $admin) {
            $workload = TrashRequest::where('assigned_staff_id', $admin->user_id)
                ->where('status', '!=', TrashRequestStateMachine::STATUS_ADMIN_APPROVED)
                ->count();
            
            $adminWorkloads[$admin->user_id] = [
                'admin' => $admin,
                'workload' => $workload,
            ];
        }

        // Sắp xếp theo workload tăng dần (ít nhiệm vụ nhất trước)
        usort($adminWorkloads, function ($a, $b) {
            return $a['workload'] <=> $b['workload'];
        });

        // Chọn admin có ít nhiệm vụ nhất
        $selectedAdmin = $adminWorkloads[0]['admin'];

        // Gán admin cho request (để track, nhưng tất cả admin đều có thể xử lý)
        $trashRequest->assigned_staff_id = $selectedAdmin->user_id;
        $trashRequest->status = TrashRequestStateMachine::STATUS_ASSIGNED;
        $trashRequest->assigned_at = now();
        $trashRequest->save();

        return $selectedAdmin;
    }

    /**
     * Lấy danh sách admin kèm số lượng nhiệm vụ hiện tại
     * (Dùng để hiển thị trong admin dashboard)
     * 
     * @return array
     */
    public function getAdminWorkloads(): array
    {
        $admins = User::where('role', 'admin')->get();

        $workloads = [];
        foreach ($admins as $admin) {
            $workloads[] = [
                'admin' => $admin,
                'active_requests' => TrashRequest::where('assigned_staff_id', $admin->user_id)
                    ->where('status', '!=', TrashRequestStateMachine::STATUS_ADMIN_APPROVED)
                    ->count(),
                'total_requests' => TrashRequest::where('assigned_staff_id', $admin->user_id)->count(),
            ];
        }

        // Sắp xếp theo số lượng nhiệm vụ đang có
        usort($workloads, function ($a, $b) {
            return $a['active_requests'] <=> $b['active_requests'];
        });

        return $workloads;
    }
}

