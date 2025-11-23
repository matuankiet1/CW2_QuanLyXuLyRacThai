<?php

namespace App\Http\Controllers;

use App\Models\CollectionSchedule;
use App\Models\Event;
use App\Models\WasteLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $upcomingEventsCount = Event::whereDate('event_start_date', '>=', $today)->count();
        //$wasteData = $this->getWasteData();
        return view('dashboard.admin', compact('upcomingEventsCount'));
    }

    public function manager()
    {
        $upcomingEvents = Event::orderBy('event_start_date', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard.manager', compact('upcomingEvents'));
    }

    public function staff()
    {
        $user = auth()->user();

        $collectionSchedules = CollectionSchedule::with('staff')
            ->where('staff_id', $user->user_id) // chỉ lấy lịch của nhân viên hiện tại
            ->orderBy('scheduled_date', 'asc')
            ->get();

        $isSearching = false; // để tránh lỗi Undefined variable

        return view('dashboard.staff', compact('collectionSchedules', 'isSearching'));
    }

    public function student()
    {
        $upcomingEvents = Event::where('status', 'upcoming')
            ->orderBy('event_start_date', 'asc')
            ->limit(5)
            ->get();

        return view('dashboard.student', compact('upcomingEvents'));
    }




}
