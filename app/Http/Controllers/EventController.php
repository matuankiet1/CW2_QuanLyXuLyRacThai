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
        $status = $request->input('status');

        $query = Event::query();

        if ($search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('location', 'like', "%$search%");
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $events = $query->orderBy('id', 'asc')->paginate(10);

        return view('admin.events.index', compact('events', 'search'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    // ✅ Tạo sự kiện mới
    public function store(Request $request)
    {
        \Log::info('Dữ liệu gửi lên:', $request->all());
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'register_date' => 'required|date',
            'register_end_date' => 'required|date',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,completed',
            'description' => 'nullable|string',
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif,webp', // ✅ Giới hạn định dạng
                'max:2048', // ✅ Giới hạn kích thước file (tính bằng KB, ở đây là 2MB)
            ],

        ]);

         if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '-' . \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;

            // Lưu vào public/images/posts
            $file->move(public_path('images/events'), $fileName);

            // Lưu đường dẫn tương đối trong DB
            $validated['image'] = 'images/events/' . $fileName;
        }

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Thêm sự kiện thành công!');
    }

    // ✅ Cập nhật sự kiện
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'register_date' => 'required|date',
            'register_end_date' => 'required|date',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:0',
            'status' => 'required|in:upcoming,completed',
            'description' => 'nullable|string',
        ]);

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Sửa sự kiện thành công!');
    }

    // ✅ Xóa sự kiện
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->back()->with('success', 'Xóa sự kiện thành công!');
    }

}
