<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // USER: Hiển thị form gửi feedback
    public function create()
    {
        return view('user.feedback.create');
    }

    // USER: Lưu feedback
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('user.feedback.index')->with('success', 'Cảm ơn bạn đã gửi phản hồi! Chúng tôi sẽ xem xét và phản hồi sớm.');
    }

    // USER: Danh sách feedback của user
    public function userIndex()
    {
        $feedback = Feedback::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('user.feedback.index', compact('feedback'));
    }

    // USER: Chi tiết feedback của user
    public function userShow(Feedback $feedback)
    {
        // Kiểm tra user chỉ được xem feedback của chính mình
        if ($feedback->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem phản hồi này.');
        }
        
        return view('user.feedback.show', compact('feedback'));
    }

    // ADMIN: Danh sách feedback (giữ nguyên)
    public function index()
    {
        $feedback = Feedback::with('user')
            ->latest()
            ->paginate(10);
            
        return view('admin.feedback.index', compact('feedback'));
    }

    // ADMIN: Chi tiết feedback (giữ nguyên)
    public function show(Feedback $feedback)
    {
        return view('admin.feedback.show', compact('feedback'));
    }

    // ADMIN: Trả lời feedback (giữ nguyên)
    public function reply(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'reply' => 'required|string|min:5',
        ]);

        $feedback->update([
            'reply' => $validated['reply'],
            'reply_at' => now(),
        ]);

        return redirect()->route('admin.feedback.show', $feedback)
            ->with('success', 'Đã gửi phản hồi thành công!');
    }
}