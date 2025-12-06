<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CollectionSchedule;
use App\Models\WasteLog;
use App\Exports\CollectionScheduleExport;
use App\Mail\NotificationMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class CollectionScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->applyFillter($request);
        $isSearch = false;
        $isFilter = false;

        if ($query) {
            $isFilter = true;
        } else {
            $query->orderByDesc('schedule_id');
        }

        $collectionSchedules = $query
            ->select('collection_schedules.*')
            ->paginate(7)
            // GIỮ LẠI TẤT CẢ THAM SỐ QUERY HIỆN CÓ (trừ page)
            ->appends($request->except('page'));

        return view('admin.collection_schedules.index', compact('collectionSchedules', 'isSearch', 'isFilter'));
    }

    public function applyFillter(Request $request)
    {
        $query = CollectionSchedule::query()->with(['staff', 'confirmedBy', 'report']);
        // 1. Sắp xếp theo nhân viên
        if ($request->radioFilterStaff === 'asc') {
            $query->leftJoin('users', 'collection_schedules.staff_id', '=', 'users.user_id')
                ->orderBy('users.name', 'asc');
        } elseif ($request->radioFilterStaff === 'desc') {
            $query->leftJoin('users', 'collection_schedules.staff_id', '=', 'users.user_id')
                ->orderBy('users.name', 'desc');
        }

        // 2. Sắp xếp theo ngày thu gom
        if ($request->radioFilterScheduledDate === 'asc') {
            $query->orderBy('scheduled_date', 'asc');
        } elseif ($request->radioFilterScheduledDate === 'desc') {
            $query->orderBy('scheduled_date', 'desc');
        }

        // 3. Sắp xếp theo ngày hoàn thành
        if ($request->radioFilterCompletedAt === 'asc') {
            $query->orderBy('completed_at', 'asc');
        } elseif ($request->radioFilterCompletedAt === 'desc') {
            $query->orderBy('completed_at', 'desc');
        }

        // 4. Lọc theo trạng thái
        if ($request->radioFilterStatus === 'Đã hoàn thành') {
            $query->where('status', 'Đã hoàn thành');
        } elseif ($request->radioFilterStatus === 'Chưa thực hiện') {
            $query->where('status', 'Chưa thực hiện');
        }

        return $query;
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
                    'message' => 'Không tìm thấy nhân viên!'
                ])->withInput();
            } else {
                $validated['staff_id'] = $staff_id;
                CollectionSchedule::create($validated);
            }
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput()->with('show_modal', true);
        }

        $user = User::where('user_id', $validated['staff_id'])->first();

        if ($user && $user->email) {
            Mail::to($user->email)->queue(new NotificationMail(
                'Thông báo về lịch thu gom rác mới',
                'Bạn có một lịch thu gom rác mới được lên lịch vào ngày ' . $validated['scheduled_date'] . '. Vui lòng kiểm tra và chuẩn bị thực hiện nhiệm vụ đúng hạn.',
                $user->name
            ));
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
        $wasteLogs = WasteLog::where('schedule_id', $id)
            ->with('wasteType') //
            ->get();
        return response()->json([$collectionSchedule, $wasteLogs]);
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
                'scheduled_date' => 'required|date',
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

    public function getWasteLogs($id)
    {
        $wasteLogs = WasteLog::where('schedule_id', $id)
            ->with('wasteType') //
            ->get();
        return response()->json($wasteLogs);
    }

    public function search(Request $request)
    {
        $q = $request->input('q');
        $collectionSchedules = CollectionSchedule::with(['staff', 'confirmedBy', 'report'])
            ->whereHas('staff', function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%');
            })
            ->orWhere('scheduled_date', 'like', '%' . $q . '%')
            ->orderBy('schedule_id', 'desc')
            ->paginate(7);
        $isSearch = true;
        return view('admin.collection_schedules.index', compact('collectionSchedules', 'isSearch', 'q'));
    }

    public function exportExcel(Request $request)
    {
        $query = $this->applyFillter($request);

        // Lấy toàn bộ dữ liệu (không phân trang)
        $rows = $query->get([
            'collection_schedules.schedule_id',
            'collection_schedules.staff_id',
            'collection_schedules.scheduled_date',
            'collection_schedules.created_at',
            'collection_schedules.status',
        ]);

        $fileName = 'Lịch thu gom rác.xlsx';

        return Excel::download(new CollectionScheduleExport($rows), $fileName);
    }

    /**
     * Update status of collection schedule
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->hasRole(['manager', 'admin'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xác nhận lịch thu gom này.'
                ], 403);
            }

            $validated = $request->validate([
                'status' => ['required', Rule::in(['Đã hoàn thành'])],
            ]);

            $collectionSchedule = CollectionSchedule::with('confirmedBy')->findOrFail($id);

            if ($collectionSchedule->confirmed_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lịch thu gom này đã được xác nhận vào ' . $collectionSchedule->confirmed_at->format('d/m/Y H:i') . '.'
                ], 422);
            }

            $collectionSchedule->status = $validated['status'];

            if (!$collectionSchedule->completed_at) {
                $collectionSchedule->completed_at = now();
            }

            $collectionSchedule->confirmed_by = $user->user_id;
            $collectionSchedule->confirmed_at = now();
            $collectionSchedule->save();

            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận lịch thu gom.',
                'status' => $collectionSchedule->status,
                'completed_at' => $collectionSchedule->completed_at?->format('Y-m-d'),
                'confirmed_at' => $collectionSchedule->confirmed_at?->format('Y-m-d H:i'),
                'confirmed_by' => $user->name,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

}
