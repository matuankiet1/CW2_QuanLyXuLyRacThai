<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Hiển thị danh sách các sự kiện với chức năng tìm kiếm và phân trang.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Xử lý logic tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
        }

        // Sắp xếp sự kiện mới nhất lên đầu và phân trang
        $events = $query->latest('date')->paginate(5);

        // Tính toán các chỉ số thống kê
        $totalEvents = Event::count();
        $totalParticipants = Event::sum('participants');
        $totalWaste = Event::sum('waste');

        // Trả về view cùng với dữ liệu
        return view('events.index', compact('events', 'totalEvents', 'totalParticipants', 'totalWaste'));
    }

    /**
     * Hiển thị form để tạo một sự kiện mới.
     * Thường dùng cho trang tạo riêng biệt. Nếu bạn dùng modal, phương thức này có thể không cần thiết.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Lưu một sự kiện mới vào cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate dữ liệu từ form
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'participants' => 'nullable|integer|min:0',
            'waste' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:upcoming,completed',
        ]);

        Event::create($validatedData);

        return redirect()->route('events.index')->with('success', 'Sự kiện đã được tạo thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết của một sự kiện cụ thể.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\View\View
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Hiển thị form để chỉnh sửa một sự kiện đã có.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\View\View
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Cập nhật thông tin một sự kiện đã có trong cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Event $event)
    {
        // Validate dữ liệu từ form
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'participants' => 'nullable|integer|min:0',
            'waste' => 'nullable|integer|min:0',
            'status' => 'required|string|in:upcoming,completed',
        ]);

        $event->update($validatedData);

        return redirect()->route('events.index')->with('success', 'Sự kiện đã được cập nhật thành công!');
    }

    /**
     * Xóa một sự kiện khỏi cơ sở dữ liệu.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Sự kiện đã được xóa thành công!');
    }
}
