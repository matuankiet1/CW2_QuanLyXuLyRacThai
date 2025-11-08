<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.admin');
    }

    public function app(){
        return view('dashboard.app');
    }

    
}
