<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dashboard.index');
    }

    public function index(){
        return view('dashboard.index');
    }
}
