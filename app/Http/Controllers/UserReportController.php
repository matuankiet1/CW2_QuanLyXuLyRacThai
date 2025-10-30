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
    public function index()
    {
        $reports = UserReport::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = UserReport::whereNull('read_at')->count();

        return view('admin.reports.user-reports', compact('reports', 'unreadCount'));
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
}
