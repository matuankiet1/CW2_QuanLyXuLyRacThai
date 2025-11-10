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

        $data = $request->validate(
            [
                'title' => 'required|string|max:255',

                // 📅 Các ngày — bắt buộc và phải hợp lý theo thứ tự logic
                'register_date' => 'required|date|before_or_equal:register_end_date|after_or_equal:today',
                'register_end_date' => 'required|date|after_or_equal:register_date|before_or_equal:event_start_date',
                'event_start_date' => 'required|date|after_or_equal:register_end_date|before_or_equal:event_end_date',
                'event_end_date' => 'required|date|after_or_equal:event_start_date',

                // 🏠 Địa điểm
                'location' => 'required|string|max:255',

                // 👥 Số người tham gia (không âm)
                'participants' => 'nullable|integer|min:0',

                // 🔖 Trạng thái chỉ được 2 loại
                'status' => 'required|in:upcoming,completed',

                // 📝 Mô tả
                'description' => 'nullable|string|max:5000',

                // 🖼 Ảnh
                'image' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,gif,webp',
                    'max:2048', // 2MB
                ],
            ],
            [
                // 🌐 Thông báo lỗi tùy chỉnh (nếu bạn muốn tiếng Việt đẹp hơn)
                'title.required' => 'Vui lòng nhập tiêu đề sự kiện.',
                'register_date.required' => 'Vui lòng chọn ngày bắt đầu đăng ký.',
                'register_end_date.required' => 'Vui lòng chọn ngày kết thúc đăng ký.',
                'event_start_date.required' => 'Vui lòng chọn ngày bắt đầu sự kiện.',
                'event_end_date.required' => 'Vui lòng chọn ngày kết thúc sự kiện.',
                'register_end_date.after_or_equal' => 'Ngày kết thúc đăng ký phải sau hoặc bằng ngày bắt đầu.',
                'event_start_date.after_or_equal' => 'Ngày bắt đầu sự kiện phải sau hoặc bằng ngày kết thúc đăng ký.',
                'event_end_date.after_or_equal' => 'Ngày kết thúc sự kiện phải sau hoặc bằng ngày bắt đầu sự kiện.',
                'location.required' => 'Vui lòng nhập địa điểm tổ chức.',
                'participants.integer' => 'Số người tham gia phải là số nguyên.',
                'participants.min' => 'Số người tham gia không được nhỏ hơn 0.',
                'status.in' => 'Trạng thái sự kiện không hợp lệ.',
                'image.image' => 'Trường hình ảnh phải là tệp ảnh hợp lệ.',
                'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png, gif hoặc webp.',
                'image.max' => 'Kích thước ảnh tối đa là 2MB.',
            ]
        );

        // 🖼 Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '-' . \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;

            // Lưu vào public/images/events
            $file->move(public_path('images/events'), $fileName);

            $data['image'] = 'images/events/' . $fileName;
        }

        // 💾 Lưu dữ liệu
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
