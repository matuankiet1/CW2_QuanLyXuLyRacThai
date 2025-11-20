<?php

namespace App\Http\Controllers;

use App\Models\CollectionReport;
use App\Models\CollectionSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CollectionReportController extends Controller
{
    /**
     * Hiển thị danh sách lịch thu gom được phân công cho nhân viên.
     */
    public function staffIndex()
    {
        $staff = Auth::user();

        $schedules = CollectionSchedule::with(['report', 'confirmedBy'])
            ->where('staff_id', $staff->user_id)
            ->orderByDesc('scheduled_date')
            ->paginate(10);

        return view('staff.collection_reports.index', compact('schedules'));
    }

    /**
     * Form báo cáo thu gom cho nhân viên.
     */
    public function staffCreate(CollectionSchedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $schedule->load(['report']);

        return view('staff.collection_reports.create', compact('schedule'));
    }

    /**
     * Lưu báo cáo thu gom từ nhân viên.
     */
    public function staffStore(Request $request, CollectionSchedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $report = $schedule->report ?: new CollectionReport([
            'schedule_id' => $schedule->schedule_id,
            'staff_id' => $schedule->staff_id,
        ]);

        $data = $request->validate([
            'total_weight' => ['required', 'numeric', 'min:0'],
            'organic_weight' => ['nullable', 'numeric', 'min:0'],
            'recyclable_weight' => ['nullable', 'numeric', 'min:0'],
            'hazardous_weight' => ['nullable', 'numeric', 'min:0'],
            'other_weight' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('photo')) {
            if ($report->photo_path) {
                Storage::disk('public')->delete($report->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('collection-reports', 'public');
        }

        $data['status'] = 'pending';
        $data['approved_by'] = null;
        $data['approved_at'] = null;

        $report->fill($data);
        $report->save();

        return redirect()
            ->route('staff.collection-reports.index')
            ->with('success', 'Báo cáo đã được gửi. Quản lý sẽ xem xét và xác nhận.');
    }

    /**
     * Danh sách báo cáo nhân viên gửi cho quản lý duyệt.
     */
    public function managerIndex(Request $request)
    {
        $reports = CollectionReport::with(['schedule.staff', 'approvedBy'])
            ->when($request->get('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        return view('admin.collection_reports.index', compact('reports'));
    }

    /**
     * Quản lý phê duyệt báo cáo.
     */
    public function approve(Request $request, CollectionReport $report)
    {
        $request->validate([
            'action' => ['required', Rule::in(['approve', 'reject'])],
            'manager_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $report->status = $request->action === 'approve' ? 'approved' : 'rejected';
        $report->approved_by = Auth::id();
        $report->approved_at = now();
        $report->save();

        if ($report->status === 'approved' && $report->schedule) {
            $report->schedule->update([
                'status' => 'Đã hoàn thành',
                'completed_at' => $report->schedule->completed_at ?? now(),
                'confirmed_by' => Auth::id(),
                'confirmed_at' => now(),
            ]);
        }

        return redirect()
            ->route('manager.collection-reports.index')
            ->with('success', $report->status === 'approved'
                ? 'Đã xác nhận báo cáo thu gom.'
                : 'Đã từ chối báo cáo thu gom.');
    }

    private function authorizeSchedule(CollectionSchedule $schedule): void
    {
        if ($schedule->staff_id !== Auth::id()) {
            abort(403, 'Bạn không được phép báo cáo lịch này.');
        }
    }
}

