<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller: UserEventController
 * 
 * Mô tả: Xử lý các chức năng của sinh viên với sự kiện
 * - Xem danh sách sự kiện
 * - Xem chi tiết sự kiện
 * - Đăng ký tham gia sự kiện
 * - Hủy đăng ký tham gia sự kiện
 */
class UserEventController extends Controller
{
    /**
     * Hiển thị danh sách sự kiện (cho sinh viên)
     * Route: GET /events
     */
    public function index(Request $request)
    {
        $query = Event::query();

        $status = $request->input('status', 'all');
        $today = now()->toDateString();

        // Filter theo status động dựa trên ngày
        if ($status && $status !== 'all') {
            $query->where(function ($q) use ($status, $today) {
                switch ($status) {
                    case 'ended':
                        $q->whereDate('event_end_date', '<', $today);
                        break;
                    case 'on_going':
                        $q->whereDate('event_start_date', '<=', $today)
                            ->whereDate('event_end_date', '>=', $today);
                        break;
                    case 'registering':
                        $q->whereDate('register_date', '<=', $today)
                            ->whereDate('register_end_date', '>=', $today);
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

        // Tìm kiếm
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sắp xếp và phân trang
        $events = $query->orderBy('event_start_date', 'asc')->paginate(12);

        // Lấy trạng thái đăng ký của user hiện tại
        $userRegistrations = [];
        if (Auth::check()) {
            $userRegistrations = EventUser::where('user_id', Auth::id())
                ->whereIn('event_id', $events->pluck('id'))
                ->pluck('status', 'event_id')
                ->toArray();
        }

        return view('user.events.index', compact('events', 'status', 'search', 'userRegistrations'));
    }


    /**
     * Hiển thị chi tiết sự kiện
     * Route: GET /events/{id}
     */
    public function show($id)
    {
        $event = Event::with(['createdBy', 'registrations.user'])
            ->findOrFail($id);

        // Kiểm tra sinh viên đã đăng ký chưa
        $userRegistration = null;
        $isRegistered = false;

        if (Auth::check()) {
            $userRegistration = EventUser::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->first();
            $isRegistered = $event->isRegisteredBy(Auth::id());
        }

        // Đếm số lượng đăng ký theo trạng thái
        $registrationStats = [
            'pending' => $event->getRegistrationCountByStatus('pending'),
            'confirmed' => $event->getRegistrationCountByStatus('confirmed'),
            'attended' => $event->getRegistrationCountByStatus('attended'),
            'total' => $event->registrations()->count(),
        ];

        return view('user.events.show', compact('event', 'userRegistration', 'isRegistered', 'registrationStats'));
    }

    

    public function showRegisterForm($id)
    {
        $event = Event::findOrFail($id);

        // Nếu bạn chỉ cho user đăng ký sự kiện chưa bắt đầu hoặc còn chỗ
        if (!$event->canRegister()) {
            return redirect()->route('user.events.show', $id)
                ->with('error', 'Sự kiện không thể đăng ký vào lúc này.');
        }

        return view('user.events.registerForm', [
            'event' => $event
        ]);
    }
    
    /**
     * Đăng ký tham gia sự kiện
     * Route: POST /events/{id}/register
     */
    public function register(Request $request, $id)
    {

        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để đăng ký tham gia sự kiện.');
        }

        $event = Event::findOrFail($id);
        $userId = Auth::id();

        // Kiểm tra sự kiện có thể đăng ký không
        if (!$event->canRegister()) {
            return redirect()->back()
                ->with('error', 'Sự kiện không thể đăng ký vào lúc này. Vui lòng kiểm tra thời gian đăng ký và số lượng chỗ còn lại.');
        }

        // Kiểm tra đã đăng ký chưa
        if ($event->isRegisteredBy($userId)) {
            return redirect()->back()
                ->with('error', 'Bạn đã đăng ký tham gia sự kiện này rồi.');
        }

        // Kiểm tra số lượng chỗ còn lại
        if (!$event->hasAvailableSlots()) {
            return redirect()->back()
                ->with('error', 'Sự kiện đã đầy. Không còn chỗ trống.');
        }

        // Đăng ký tham gia
        try {
            DB::beginTransaction();

            EventUser::create([
                'user_id' => $userId,
                'event_id' => $event->id,
                'name' => $request->input('name'),
                'student_id' => $request->input('student_id'),
                'student_class' => $request->input('student_class'),
                'email' => $request->input('email'),
                'status' => 'pending',
                'registered_at' => now(),
            ]);


            // Cập nhật số lượng tham gia
            $event->increment('participants');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Đăng ký tham gia sự kiện thành công! Vui lòng chờ admin xác nhận.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log ra laravel.log để biết nguyên nhân
            \Log::error('Đăng ký sự kiện thất bại: ' . $e->getMessage(), [
                'user_id' => $userId,
                'event_id' => $event->id,
                'request' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại. Chi tiết trong log.');
        }
    }

    /**
     * Hủy đăng ký tham gia sự kiện
     * Route: DELETE /events/{id}/cancel
     */
    public function cancel($id)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để hủy đăng ký.');
        }

        $event = Event::findOrFail($id);
        $userId = Auth::id();

        // Tìm đăng ký
        $registration = EventUser::where('user_id', $userId)
            ->where('event_id', $event->id)
            ->first();

        if (!$registration) {
            return redirect()->back()
                ->with('error', 'Bạn chưa đăng ký tham gia sự kiện này.');
        }

        // Kiểm tra trạng thái (chỉ hủy được khi đang pending hoặc confirmed)
        if (!in_array($registration->status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'Không thể hủy đăng ký. Trạng thái: ' . $registration->status);
        }

        // Hủy đăng ký
        try {
            DB::beginTransaction();

            $registration->cancel();

            // Giảm số lượng tham gia
            $event->decrement('participants');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Hủy đăng ký thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi hủy đăng ký. Vui lòng thử lại.');
        }
    }
}
