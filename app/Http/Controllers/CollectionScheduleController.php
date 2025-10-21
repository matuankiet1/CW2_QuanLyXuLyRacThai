<?php

namespace App\Http\Controllers;

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
        return view('admin.collection_schedules.index');
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
            'staff_id' => 'required|exists:users,user_id',
            'scheduled_date' => 'required|date',
            'status' => ['required', Rule::in(['Chưa thực hiện', 'Đã hoàn thành'])],
            'completed_at' => 'nullable|date',
        ]);
        CollectionSchedule::create($validated);
        return back()->with('status', [
            'type' => 'success',
            'message' => 'Added collection schedule successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CollectionSchedule $collectionScheduleManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollectionSchedule $collectionScheduleManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CollectionSchedule $collectionScheduleManagement)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,user_id',
            'scheduled_date' => 'required|date',
            'status' => ['required', Rule::in(['Chưa thực hiện', 'Đã hoàn thành'])],
            'completed_at' => 'nullable|date',
        ]);
        $collectionScheduleManagement->update($validated);
        return back()->with('status', [
                'type' => 'success',
                'message' => 'Updated collection schedule successfully!'
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
                'message' => 'Deleted collection schedule successfully!'
            ]);
        } else {
            return back()->with('status', [
                'type' => 'error',
                'message' => 'Something went error, please try again later.'
            ]);
        }
    }
}
