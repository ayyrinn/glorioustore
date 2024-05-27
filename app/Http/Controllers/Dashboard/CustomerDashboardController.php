<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    /**
     * Display the dashboard for customers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('customerdashboard.dashboard');
    }
}
