<?php

namespace App\Http\Controllers;

use App\Models\TrashRequest;
use App\Models\User;
use App\Services\TrashRequestStateMachine;
use App\Services\TrashRequestAutoAssignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller xử lý yêu cầu thu gom rác
 * 
 * Phân quyền:
 * - Student: Gửi yêu cầu, xem yêu cầu của mình
 * - Staff: Xem nhiệm vụ được gán, cập nhật trạng thái, upload ảnh minh chứng
 * - Admin: Duyệt/từ chối yêu cầu, xem tất cả yêu cầu
 */
class TrashRequestController extends Controller
{
    protected $autoAssignService;

    public function __construct(TrashRequestAutoAssignService $autoAssignService)
    {
        $this->autoAssignService = $autoAssignService;
    }

    // ==================== STUDENT METHODS ====================

    /**
     * Hiển thị danh sách yêu cầu của student
     */
    public function studentIndex(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        $status = $request->input('status', 'all');
        $query = $user->trashRequests();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $trashRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('student.trash-requests.index', compact('trashRequests', 'status'));
    }

    /**
     * Hiển thị form tạo yêu cầu mới (Student)
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        return view('student.trash-requests.create');
    }

    /**
     * Lưu yêu cầu mới (Student)
     * Sau khi lưu, hệ thống tự động gán staff
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        // Validation
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120', // Max 5MB
        ]);

        // Upload ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $folder = 'trash-requests';
            $imagePath = $file->storeAs($folder, $filename, 'public');
        }

        // Tạo yêu cầu với trạng thái pending
        $trashRequest = TrashRequest::create([
            'student_id' => $user->user_id,
            'location' => $validated['location'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => TrashRequestStateMachine::STATUS_PENDING,
        ]);

        // Tự động gán admin
        $assignedAdmin = $this->autoAssignService->autoAssign($trashRequest);

        if (!$assignedAdmin) {
            return redirect()->route('student.trash-requests.index')
                ->with('warning', 'Yêu cầu đã được tạo nhưng chưa có admin nào để gán. Vui lòng liên hệ quản trị viên.');
        }

        return redirect()->route('student.trash-requests.index')
            ->with('success', 'Yêu cầu thu gom rác đã được gửi và sẽ được xử lý bởi quản trị viên.');
    }

    /**
     * Xem chi tiết yêu cầu (Student)
     */
    public function studentShow($id)
    {
        $user = Auth::user();
        $trashRequest = TrashRequest::findOrFail($id);

        if (!$trashRequest->belongsToStudent($user->user_id)) {
            return redirect()->route('student.trash-requests.index')
                ->with('error', 'Bạn không có quyền xem yêu cầu này.');
        }

        return view('student.trash-requests.show', compact('trashRequest'));
    }

    // ==================== STAFF METHODS ====================

    /**
     * Hiển thị danh sách nhiệm vụ được gán cho staff/admin
     * Admin có thể xem tất cả yêu cầu
     */
    public function staffIndex(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isStaff() && !$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        $status = $request->input('status', 'all');
        $query = TrashRequest::with(['student', 'assignedStaff']);

        // Staff chỉ xem nhiệm vụ được gán cho mình, Admin xem tất cả
        if ($user->isStaff()) {
            $query->where('assigned_staff_id', $user->user_id);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $trashRequests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('staff.trash-requests.index', compact('trashRequests', 'status'));
    }

    /**
     * Xem chi tiết nhiệm vụ (Staff/Admin)
     * Admin có thể xem tất cả
     */
    public function staffShow($id)
    {
        $user = Auth::user();
        $trashRequest = TrashRequest::with(['student', 'assignedStaff'])->findOrFail($id);

        // Admin có thể xem tất cả, Staff chỉ xem được gán cho mình
        if (!$user->isAdmin() && !$trashRequest->isAssignedToStaff($user->user_id)) {
            return redirect()->route('staff.trash-requests.index')
                ->with('error', 'Bạn không có quyền xem nhiệm vụ này.');
        }

        return view('staff.trash-requests.show', compact('trashRequest'));
    }

    /**
     * Hiển thị form cập nhật nhiệm vụ (Staff/Admin)
     * Admin có thể cập nhật tất cả
     */
    public function staffEdit($id)
    {
        $user = Auth::user();
        $trashRequest = TrashRequest::findOrFail($id);

        // Admin có thể cập nhật tất cả, Staff chỉ cập nhật được gán cho mình
        if (!$user->isAdmin() && !$trashRequest->isAssignedToStaff($user->user_id)) {
            return redirect()->route('staff.trash-requests.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa nhiệm vụ này.');
        }

        // Chỉ cho phép chỉnh sửa khi status là assigned, staff_done, hoặc admin_rejected
        $allowedStatuses = [
            TrashRequestStateMachine::STATUS_ASSIGNED,
            TrashRequestStateMachine::STATUS_STAFF_DONE,
            TrashRequestStateMachine::STATUS_ADMIN_REJECTED,
        ];

        if (!in_array($trashRequest->status, $allowedStatuses)) {
            return redirect()->route('staff.trash-requests.show', $id)
                ->with('error', 'Không thể chỉnh sửa nhiệm vụ ở trạng thái này.');
        }

        return view('staff.trash-requests.edit', compact('trashRequest'));
    }

    /**
     * Cập nhật nhiệm vụ (Staff/Admin)
     * Sau khi cập nhật, tự động chuyển sang waiting_admin
     * Admin có thể cập nhật tất cả
     */
    public function staffUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $trashRequest = TrashRequest::findOrFail($id);

        // Admin có thể cập nhật tất cả, Staff chỉ cập nhật được gán cho mình
        if (!$user->isAdmin() && !$trashRequest->isAssignedToStaff($user->user_id)) {
            return redirect()->route('staff.trash-requests.index')
                ->with('error', 'Bạn không có quyền cập nhật nhiệm vụ này.');
        }

        // Validation
        $validated = $request->validate([
            'proof_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'staff_notes' => 'nullable|string|max:2000',
        ]);

        // Upload ảnh minh chứng nếu có
        $proofImagePath = $trashRequest->proof_image; // Giữ ảnh cũ nếu không upload mới
        if ($request->hasFile('proof_image')) {
            // Xóa ảnh cũ nếu có
            if ($trashRequest->proof_image) {
                Storage::disk('public')->delete($trashRequest->proof_image);
            }

            $file = $request->file('proof_image');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $folder = 'trash-requests/proof';
            $proofImagePath = $file->storeAs($folder, $filename, 'public');
        }

        // Cập nhật thông tin
        $trashRequest->proof_image = $proofImagePath;
        $trashRequest->staff_notes = $validated['staff_notes'] ?? null;

        // Chuyển trạng thái: assigned → staff_done → waiting_admin
        // Hoặc admin_rejected → staff_done → waiting_admin
        if ($trashRequest->status === TrashRequestStateMachine::STATUS_ASSIGNED || 
            $trashRequest->status === TrashRequestStateMachine::STATUS_ADMIN_REJECTED) {
            $trashRequest->transitionTo(TrashRequestStateMachine::STATUS_STAFF_DONE, [
                'proof_image' => $proofImagePath,
                'staff_notes' => $validated['staff_notes'] ?? null,
            ]);
        } else {
            // Nếu đã là staff_done, chỉ cập nhật thông tin
            $trashRequest->save();
        }

        return redirect()->route('staff.trash-requests.show', $id)
            ->with('success', 'Nhiệm vụ đã được cập nhật và đang chờ admin duyệt.');
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Hiển thị danh sách tất cả yêu cầu (Admin)
     */
    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        $status = $request->input('status', 'all');
        $search = $request->input('search');
        $query = TrashRequest::with(['student', 'assignedStaff']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('location', 'like', "%$search%")
                  ->orWhere('type', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('student', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  })
                  ->orWhereHas('assignedStaff', function ($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }

        $trashRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Thống kê
        $stats = [
            'total' => TrashRequest::count(),
            'pending' => TrashRequest::where('status', TrashRequestStateMachine::STATUS_PENDING)->count(),
            'assigned' => TrashRequest::where('status', TrashRequestStateMachine::STATUS_ASSIGNED)->count(),
            'waiting_admin' => TrashRequest::where('status', TrashRequestStateMachine::STATUS_WAITING_ADMIN)->count(),
            'approved' => TrashRequest::where('status', TrashRequestStateMachine::STATUS_ADMIN_APPROVED)->count(),
            'rejected' => TrashRequest::where('status', TrashRequestStateMachine::STATUS_ADMIN_REJECTED)->count(),
        ];

        return view('admin.trash-requests.index', compact('trashRequests', 'status', 'search', 'stats'));
    }

    /**
     * Xem chi tiết yêu cầu (Admin)
     */
    public function adminShow($id)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        $trashRequest = TrashRequest::with(['student', 'assignedStaff'])->findOrFail($id);

        return view('admin.trash-requests.show', compact('trashRequest'));
    }

    /**
     * Duyệt yêu cầu (Admin)
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        $trashRequest = TrashRequest::findOrFail($id);

        // Cho phép duyệt từ trạng thái "assigned" hoặc "waiting_admin"
        if (!in_array($trashRequest->status, ['assigned', 'waiting_admin'])) {
            return redirect()->route('admin.trash-requests.show', $id)
                ->with('error', 'Chỉ có thể duyệt yêu cầu ở trạng thái "Đã gán" hoặc "Chờ duyệt".');
        }
        
        // Nếu đang ở trạng thái "assigned", chuyển sang "waiting_admin" trước
        if ($trashRequest->status === 'assigned') {
            $trashRequest->status = TrashRequestStateMachine::STATUS_WAITING_ADMIN;
            $trashRequest->save();
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $trashRequest->transitionTo(TrashRequestStateMachine::STATUS_ADMIN_APPROVED, [
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return redirect()->route('admin.trash-requests.show', $id)
            ->with('success', 'Yêu cầu đã được duyệt thành công.');
    }

    /**
     * Từ chối yêu cầu (Admin)
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập.');
        }

        $trashRequest = TrashRequest::findOrFail($id);

        // Cho phép từ chối từ trạng thái "assigned" hoặc "waiting_admin"
        if (!in_array($trashRequest->status, ['assigned', 'waiting_admin'])) {
            return redirect()->route('admin.trash-requests.show', $id)
                ->with('error', 'Chỉ có thể từ chối yêu cầu ở trạng thái "Đã gán" hoặc "Chờ duyệt".');
        }
        
        // Nếu đang ở trạng thái "assigned", chuyển sang "waiting_admin" trước
        if ($trashRequest->status === 'assigned') {
            $trashRequest->status = TrashRequestStateMachine::STATUS_WAITING_ADMIN;
            $trashRequest->save();
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:2000', // Bắt buộc có lý do từ chối
        ]);

        $trashRequest->transitionTo(TrashRequestStateMachine::STATUS_ADMIN_REJECTED, [
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->route('admin.trash-requests.show', $id)
            ->with('success', 'Yêu cầu đã bị từ chối. Staff có thể cập nhật lại.');
    }
}
