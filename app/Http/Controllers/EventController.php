<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsExport;
use Carbon\Carbon;

class EventController extends Controller
{
    // ✅ Hiển thị danh sách sự kiện
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all');

        $query = Event::query();

        // 🔍 Filter theo từ khóa
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('location', 'like', "%$search%");
            });
        }

        $today = Carbon::today();

        // ⚡ Filter theo dynamic status
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where(function ($q) use ($statusFilter, $today) {
                switch ($statusFilter) {
                    case 'ended':
                        $q->whereDate('event_end_date', '<', $today);
                        break;
                    case 'on_going':
                        $q->whereDate('event_start_date', '<=', $today)
                            ->whereDate('event_end_date', '>=', $today);
                        break;
                    case 'registering':
                        $q->whereDate('register_end_date', '>=', $today)
                            ->whereDate('register_date', '<=', $today);
                        break;
                    case 'register_ended':
                        $q->whereDate('register_end_date', '<', $today)
                            ->whereDate('event_start_date', '>', $today);
                        break;
                    case 'up_coming':
                        $q->whereDate('register_date', '>', $today);
                        break;
                }
            });
        }

        $events = $query
            ->withCount([
                'participants as attended_participants_count' => function ($q) {
                    $q->where('status', 'attended');
                },
                'participants as pending_participants_count' => function ($q) {
                    $q->where('status', 'pending');
                },
                'participants as attending_participants_count' => function ($q) {
                    $q->where('status', 'confirmed');
                },
            ])
            ->orderBy('id', 'asc')
            ->paginate(10);



        return view('admin.events.index', compact('events', 'search', 'statusFilter'));
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
        Log::info('Dữ liệu gửi lên:', $request->all());

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'register_date' => 'required|date|before_or_equal:register_end_date|after_or_equal:today',
            'register_end_date' => 'required|date|after_or_equal:register_date|before_or_equal:event_start_date',
            'event_start_date' => 'required|date|after_or_equal:register_end_date|before_or_equal:event_end_date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:0',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:5000',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề sự kiện.',
            'title.max' => 'Tên sự kiện không được vượt quá 255 ký tự.',
            'register_date.required' => 'Vui lòng chọn ngày bắt đầu đăng ký.',
            'register_end_date.required' => 'Vui lòng chọn ngày kết thúc đăng ký.',
            'event_start_date.required' => 'Vui lòng chọn ngày bắt đầu sự kiện.',
            'event_end_date.required' => 'Vui lòng chọn ngày kết thúc sự kiện.',
            'register_date.after_or_equal' => 'Ngày bắt đầu đăng ký phải bắt đầu từ ngày hôm nay.',
            'register_date.before_or_equal' => 'Ngày bắt đầu đăng ký phải trước ngày kết thúc đăng ký.',
            'register_end_date.after_or_equal' => 'Ngày kết thúc đăng ký phải sau hoặc bằng ngày bắt đầu đăng ký.',
            'register_end_date.before_or_equal' => 'Ngày kết thúc đăng ký phải trước ngày bắt đầu sự kiện.',
            'event_start_date.after_or_equal' => 'Ngày bắt đầu sự kiện phải sau hoặc bằng ngày kết thúc đăng ký.',
            'event_start_date.before_or_equal' => 'Ngày bắt đầu sự kiện phải trước ngày kết thúc sự kiện.',
            'event_end_date.after_or_equal' => 'Ngày kết thúc sự kiện phải sau hoặc bằng ngày bắt đầu sự kiện.',
            'location.required' => 'Vui lòng nhập địa điểm tổ chức.',
            'participants.integer' => 'Số người tham gia phải là số nguyên.',
            'participants.min' => 'Số người tham gia không được nhỏ hơn 0.',
            'description.max' => 'Mô tả sự kiện không được vượt quá 5000 ký tự',
            'image.image' => 'Trường hình ảnh phải là tệp ảnh hợp lệ.',
            'image.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png, gif hoặc webp.',
            'image.max' => 'Kích thước ảnh tối đa là 2MB.',
        ]);

        // 2️⃣ Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            $file->move(public_path('images/events'), $fileName);

            $data['image'] = 'images/events/' . $fileName;
        }

        // Thêm created_by (người tạo sự kiện)
        $data['created_by'] = Auth::user()->user_id;

        // 3️⃣ Lưu dữ liệu cơ bản (không lưu status)
        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Thêm sự kiện thành công!');
    }


    // ✅ Cập nhật sự kiện
    public function update(Request $request, Event $event)
    {
        Log::info('Cập nhật sự kiện ID: ' . $event->id, $request->all());

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'register_date' => 'required|date|after_or_equal:today',
            'register_end_date' => 'required|date|after_or_equal:register_date|before_or_equal:event_start_date',
            'event_start_date' => 'required|date|after_or_equal:register_end_date|before_or_equal:event_end_date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'location' => 'required|string|max:255',
            'participants' => 'nullable|integer|min:0',
            'capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:5000',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề sự kiện.',
            'title.max' => 'Tên sự kiện không được vượt quá 255 ký tự.',
            'register_date.required' => 'Vui lòng chọn ngày bắt đầu đăng ký.',
            'register_end_date.required' => 'Vui lòng chọn ngày kết thúc đăng ký.',
            'event_start_date.required' => 'Vui lòng chọn ngày bắt đầu sự kiện.',
            'event_end_date.required' => 'Vui lòng chọn ngày kết thúc sự kiện.',
            'register_date.after_or_equal' => 'Ngày bắt đầu đăng ký phải bắt đầu từ ngày hôm nay.',
            'register_date.before_or_equal' => 'Ngày bắt đầu đăng ký phải trước ngày kết thúc đăng ký.',
            'register_end_date.after_or_equal' => 'Ngày kết thúc đăng ký phải sau hoặc bằng ngày bắt đầu đăng ký.',
            'register_end_date.before_or_equal' => 'Ngày kết thúc đăng ký phải trước ngày bắt đầu sự kiện.',
            'event_start_date.after_or_equal' => 'Ngày bắt đầu sự kiện phải sau hoặc bằng ngày kết thúc đăng ký.',
            'event_start_date.before_or_equal' => 'Ngày bắt đầu sự kiện phải trước ngày kết thúc sự kiện.',
            'event_end_date.after_or_equal' => 'Ngày kết thúc sự kiện phải sau hoặc bằng ngày bắt đầu sự kiện.',
            'location.required' => 'Vui lòng nhập địa điểm tổ chức.',
            'location.max' => 'Địa điểm không được vượt quá 255 ký tự.',
            'participants.integer' => 'Số người tham gia phải là số nguyên.',
            'participants.min' => 'Số người tham gia không được nhỏ hơn 0.',
            'description.max' => 'Mô tả sự kiện không được vượt quá 5000 ký tự',
            'image.image' => 'Tệp tải lên phải là ảnh hợp lệ.',
            'image.mimes' => 'Ảnh chỉ được phép có định dạng: jpg, jpeg, png, gif, webp.',
            'image.max' => 'Kích thước ảnh tối đa là 2MB.',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image && file_exists(public_path($event->image))) {
                @unlink(public_path($event->image));
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            $file->move(public_path('images/events'), $fileName);

            $data['image'] = 'images/events/' . $fileName;
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Sửa sự kiện thành công!');
    }



    // ✅ Xóa sự kiện
    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Sự kiện không tồn tại hoặc đã bị xoá.');
        }

        $event->delete();

        return redirect()->back()->with('success', 'Xóa sự kiện thành công!');
    }

}
