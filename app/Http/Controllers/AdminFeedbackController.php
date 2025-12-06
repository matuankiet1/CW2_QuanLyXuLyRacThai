<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index()
    {
        $feedback = Feedback::with('user')->latest()->get();
        return view('admin.feedback.index', compact('feedback'));
    }

    public function show($id)
    {
        $fb = Feedback::with('user')->findOrFail($id);
        return view('admin.feedback.show', compact('fb'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|min:5'
        ]);

        $fb = Feedback::findOrFail($id);
        $fb->update([
            'admin_reply' => $request->admin_reply,
            'replied_at'  => now(),
        ]);

        // Gửi realtime khi admin trả lời
        event(new FeedbackReplied($fb));

        return back()->with('success', 'Đã gửi phản hồi tới người dùng');
    }
}
