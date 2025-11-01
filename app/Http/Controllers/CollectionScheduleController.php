<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CollectionSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CollectionScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CollectionSchedule::query()->with('staff');

        // 1. Sắp xếp theo nhân viên
        if ($request->radioFilterStaff === 'asc') {
            $query->join('users', 'collection_schedules.staff_id', '=', 'users.id')
                ->orderBy('users.name', 'asc');
        } elseif ($request->radioFilterStaff === 'desc') {
            $query->join('users', 'collection_schedules.staff_id', '=', 'users.id')
                ->orderBy('users.name', 'desc');
        }

        // 2. Sắp xếp theo ngày thu gom
        if ($request->radioFilterScheduledDate === 'old_new') {
            $query->orderBy('scheduled_date', 'asc');
        } elseif ($request->radioFilterScheduledDate === 'new_old') {
            $query->orderBy('scheduled_date', 'desc');
        }

        // 3. Sắp xếp theo ngày hoàn thành
        if ($request->radioFilterCompletedAt === 'old_new') {
            $query->orderBy('completed_at', 'asc');
        } elseif ($request->radioFilterCompletedAt === 'new_old') {
            $query->orderBy('completed_at', 'desc');
        }

        // 4. Lọc theo trạng thái
        if ($request->radioFilterStatus === 'Đã hoàn thành') {
            $query->where('status', 'Đã hoàn thành');
        } elseif ($request->radioFilterStatus === 'Chưa thực hiện') {
            $query->where('status', 'Chưa thực hiện');
        }
        $collectionSchedules = $query->select('collection_schedules.*')->paginate(7);
        $isSearching = false;
        
        // Nếu request là AJAX, chỉ trả về phần bảng
        if ($request->ajax()) {
            return view('admin.collection-schedules._table', compact('collectionSchedules'))->render();
        }
        return view('admin.collection_schedules.index', compact('collectionSchedules', 'isSearching'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'staff_id' => 'required|string|max:255',
                'scheduled_date' => 'required|date|after_or_equal:now'
            ]);
            $staff_id = User::where('name', $validated['staff_id'])->value('user_id');
            if (!$staff_id) {
                return back()->with('status', [
                    'type' => 'error',
                    'message' => 'Staff not found!'
                ])->withInput();
            } else {
                $validated['staff_id'] = $staff_id;
                CollectionSchedule::create($validated);
            }
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput()->with('show_modal', true);
        }
        return back()->with('status', [
            'type' => 'success',
            'message' => 'Thêm lịch thu gom thành công!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $collectionSchedule = CollectionSchedule::with('staff')->findOrFail($id);
        return response()->json($collectionSchedule);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $staff_name = $request['staff_id'];
        if ($staff_name) {
            $staff_id = User::where('name', $staff_name)->value('user_id');
            if (!$staff_id) {
                return back()->with('status', [
                    'type' => 'error',
                    'message' => 'Không tìm thấy nhân viên. Vui lòng thử lại sau!'
                ])->withInput();
            } else {
                $request['staff_id'] = $staff_id;
            }
        }

        try {
            $validated = $request->validate([
                'staff_id' => 'required|exists:users,user_id',
                'scheduled_date' => 'required|date|after_or_equal:now',
                'completed_at' => 'nullable|date|after_or_equal:now',
                'status' => ['required', Rule::in(['Chưa thực hiện', 'Đã hoàn thành'])],
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput()->with('show_modal', true);
        }

        $collectionSchedule = CollectionSchedule::findOrFail($id);

        if (!$collectionSchedule) {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Không tìm thấy lịch thu gom. Vui lòng thử lại sau!'
            ])->withInput();
        }

        $collectionSchedule->update($validated);

        return back()->with('status', [
            'type' => 'success',
            'message' => 'Cập nhật lịch thu gom thành công!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $collectionSchedule = CollectionSchedule::findOrFail(($id));
        if ($collectionSchedule) {
            $collectionSchedule->delete();
            return back()->with('status', [
                'type' => 'success',
                'message' => 'Xóa lịch thu gom thành công!'
            ]);
        } else {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Có sự cố xảy ra, vui lòng thử lại sau!'
            ]);
        }
    }

    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids'); // Mảng các ID được chọn

        if (!$ids || count($ids) === 0) {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Vui lòng chọn ít nhất một bản ghi để xóa!'
            ]);
        }

        CollectionSchedule::whereIn('schedule_id', $ids)->delete();

        return back()->with('status', [
            'type' => 'success',
            'message' => 'Xóa ' . count($ids) . ' lịch thu gom đã chọn thành công!'
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        $collectionSchedules = CollectionSchedule::whereHas('staff', function ($query) use ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        })->orWhere('scheduled_date', 'like', '%' . $q . '%')
            ->orderBy('schedule_id', 'desc')
            ->paginate(7);
        $isSearching = true;
        return view('admin.collection_schedules.index', compact('collectionSchedules', 'isSearching', 'q'));
    }

}
