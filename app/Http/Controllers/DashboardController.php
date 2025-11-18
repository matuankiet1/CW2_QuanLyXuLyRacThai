<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $upcomingEventsCount = Event::where('status', 'upcoming')->whereDate('event_start_date', '>=', $today)->count();

        return view('dashboard.admin', compact('upcomingEventsCount'));
    }

    public function app()
    {
        return view('dashboard.app');
    }

    


}
