<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get statistics from view
        $stats = DB::table('v_dashboard_summary')->first();
        
        return view('welcome', compact('stats'));
    }
}
