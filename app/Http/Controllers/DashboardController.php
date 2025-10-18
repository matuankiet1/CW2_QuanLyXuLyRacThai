<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Có thể truyền dữ liệu động sau này, tạm thời chỉ hiển thị view
        return view('dashboard.admin');
    }
}
