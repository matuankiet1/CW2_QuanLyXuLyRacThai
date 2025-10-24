<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CollectionSchedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CollectionScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collectionSchedules = CollectionSchedule::orderBy('schedule_id', 'desc')->paginate(10);
        return view('admin.collection_schedules.index', compact('collectionSchedules'));
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
        $validated = $request->validate([
            'staff_id' => 'required|string|max:255',
            'scheduled_date' => 'required|date'
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
        if ($request['staff_id']) {
            $staff_id = User::where('name', $request['staff_id'])->value('user_id');
            if (!$staff_id) {
                return back()->with('status', [
                    'type' => 'error',
                    'message' => 'Không tìm thấy nhân viên. Vui lòng thử lại sau!'
                ])->withInput();
            } else {
                $request['staff_id'] = $staff_id;
            }
        }
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,user_id',
            'scheduled_date' => 'required|date',
        ]);
        // $collectionScheduleManagement->update($validated);
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
            'message' => 'Cap nhật lịch thu gom thành công!'
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

    public function search(Request $request)
    {
        $q = $request->input('q');
        $collectionSchedules = CollectionSchedule::whereHas('staff', function ($query) use ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        })->orWhere('scheduled_date', 'like', '%' . $q . '%')
            ->orderBy('schedule_id', 'desc')
            ->paginate(10);
        // dd($collectionSchedules);    
        return view('admin.collection_schedules.index', compact('collectionSchedules', 'q'));
    }

}
