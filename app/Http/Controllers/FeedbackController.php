<?php

namespace App\Http\Controllers;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('user.feedback.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required|min:10',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Gửi event realtime
        event(new FeedbackCreated());

        return back()->with('success', 'Cảm ơn bạn đã gửi phản hồi!');
    }

    public function index()
    {
        $feedback = Feedback::with('user')->latest()->paginate(10);
        return view('admin.feedback.index', compact('feedback'));
    }

    public function show(Feedback $feedback)
    {
        return view('admin.feedback.show', compact('feedback'));
    }

    public function reply(Request $request, Feedback $feedback)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        $feedback->update([
            'reply' => $request->reply,
            'reply_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đã trả lời phản hồi.');
    }
}
