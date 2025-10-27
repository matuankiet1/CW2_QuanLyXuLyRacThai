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
    public function index()
    {
        $collectionSchedules = CollectionSchedule::orderBy('schedule_id', 'desc')->paginate(7);
        $isSearching = false;
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
    {   $staff_name = $request['staff_id'];
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
