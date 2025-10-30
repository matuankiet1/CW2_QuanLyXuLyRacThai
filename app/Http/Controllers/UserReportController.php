<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserReportController extends Controller
{
    /**
     * Hiển thị form tạo báo cáo
     */
    public function create()
    {
        return view('user.reports.create');
    }

    /**
     * Lưu báo cáo mới
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:complaint,suggestion,bug,other',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        UserReport::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'status' => 'pending',
        ]);

        return redirect()->route('user.reports.create')
            ->with('success', 'Báo cáo của bạn đã được gửi thành công! Chúng tôi sẽ xem xét và phản hồi sớm nhất có thể.');
    }

    /**
     * Danh sách báo cáo của admin
     */
    public function index(Request $request)
    {
        // Xử lý cập nhật trạng thái nếu có request
        if ($request->has('action')) {
            $this->handleBulkAction($request);
        }

        // Xử lý filter theo trạng thái
        $query = UserReport::with('user');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('read_status')) {
            if ($request->read_status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->read_status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $reports = $query->orderBy('created_at', 'desc')->get();
        $unreadCount = UserReport::whereNull('read_at')->count();

        // Thống kê
        $stats = [
            'total' => UserReport::count(),
            'pending' => UserReport::where('status', 'pending')->count(),
            'processing' => UserReport::where('status', 'processing')->count(),
            'resolved' => UserReport::where('status', 'resolved')->count(),
            'unread' => $unreadCount,
        ];

        return view('admin.reports.user-reports-simple', compact('reports', 'unreadCount', 'stats'));
    }

    /**
     * Xử lý các hành động hàng loạt
     */
    private function handleBulkAction(Request $request)
    {
        $action = $request->input('action');
        $reportIds = $request->input('report_ids', []);

        if (empty($reportIds)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một báo cáo.');
        }

        switch ($action) {
            case 'mark_read':
                UserReport::whereIn('id', $reportIds)->update(['read_at' => now()]);
                return redirect()->back()->with('success', 'Đã đánh dấu ' . count($reportIds) . ' báo cáo là đã đọc.');
                
            case 'mark_unread':
                UserReport::whereIn('id', $reportIds)->update(['read_at' => null]);
                return redirect()->back()->with('success', 'Đã đánh dấu ' . count($reportIds) . ' báo cáo là chưa đọc.');
                
            case 'update_status':
                $status = $request->input('status');
                if (in_array($status, ['pending', 'processing', 'resolved'])) {
                    UserReport::whereIn('id', $reportIds)->update(['status' => $status]);
                    return redirect()->back()->with('success', 'Đã cập nhật trạng thái ' . count($reportIds) . ' báo cáo thành ' . $status . '.');
                }
                break;
                
            case 'delete':
                UserReport::whereIn('id', $reportIds)->delete();
                return redirect()->back()->with('success', 'Đã xóa ' . count($reportIds) . ' báo cáo.');
        }

        return redirect()->back()->with('error', 'Hành động không hợp lệ.');
    }

    /**
     * Xem chi tiết báo cáo
     */
    public function show($id)
    {
        $report = UserReport::with('user')->findOrFail($id);
        
        // Đánh dấu là đã đọc
        if (!$report->isRead()) {
            $report->markAsRead();
        }

        return view('admin.reports.user-report-detail', compact('report'));
    }

    /**
     * Cập nhật trạng thái báo cáo
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,resolved',
        ]);

        $report = UserReport::findOrFail($id);
        $report->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Trạng thái báo cáo đã được cập nhật!');
    }

    /**
     * Đánh dấu báo cáo đã đọc
     */
    public function markAsRead($id)
    {
        $report = UserReport::findOrFail($id);
        $report->markAsRead();

        return response()->json(['success' => true, 'message' => 'Báo cáo đã được đánh dấu là đã đọc.']);
    }

    /**
     * Đánh dấu báo cáo chưa đọc
     */
    public function markAsUnread($id)
    {
        $report = UserReport::findOrFail($id);
        $report->update(['read_at' => null]);

        return response()->json(['success' => true, 'message' => 'Báo cáo đã được đánh dấu là chưa đọc.']);
    }

    /**
     * Cập nhật trạng thái báo cáo qua AJAX
     */
    public function updateStatusAjax(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,resolved',
        ]);

        $report = UserReport::findOrFail($id);
        $report->update(['status' => $request->status]);

        return response()->json([
            'success' => true, 
            'message' => 'Trạng thái báo cáo đã được cập nhật thành ' . $request->status . '.',
            'status' => $request->status
        ]);
    }
}
