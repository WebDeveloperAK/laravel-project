<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
    public function index()
    {
        // $totalUsers = Auth::user()->role === 'admin' ? User::count() : null;
        $totalUsers = User::count();
        $latestMessage = DB::table('message')
    ->where('created_at', '>=', now()->subMinutes(10)) 
    ->count();
// dd($latestMessage);
    $userRegistrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(id) as count'))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

        $userMessages = DB::select("
        SELECT DATE(created_at) AS date, COUNT(id) AS count
        FROM message
        GROUP BY date
        ORDER BY date ASC
    ");;

    return view('dashboard', compact('totalUsers', 'userRegistrations','userMessages','latestMessage'));
    }
}
