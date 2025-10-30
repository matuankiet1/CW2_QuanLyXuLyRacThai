<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsExport;

class EventController extends Controller
{
    // ✅ Hiển thị danh sách sự kiện
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Event::query();

        if ($search) {
            $query->where('title', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%");
        }

        $events = $query->orderBy('date', 'desc')->paginate(10);

        return view('admin.events.index', compact('events', 'search'));
    }

    public function create(){
        return view('admin.events.create');
    }

    // ✅ Tạo sự kiện mới
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,completed',
            'waste' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Event::create($data);

        return redirect()->back()->with('success', 'Thêm sự kiện thành công!');
    }

    // ✅ Cập nhật sự kiện
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,completed',
            'waste' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $event->update($data);

        return redirect()->back()->with('success', 'Cập nhật sự kiện thành công!');
    }

    // ✅ Xóa sự kiện
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Xóa sự kiện thành công!');
    }

}
