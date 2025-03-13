<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ApiController extends Controller
{
    //
    public function fetchData(Request $request){

        if ($request->ajax()) {
            try {
                $response = Http::get('https://reqres.in/api/users?page=1');
                
                $users = $response->json()['data'] ?? []; 
    
                return DataTables::of($users)
                ->addColumn('full_name', function ($user) {
                    return $user['first_name'] . ' ' . $user['last_name'];
                })
                    ->addColumn('action', function ($user) {
                        return '<a href="#" class="btn btn-primary">Edit</a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        return view('api');
    }
    


public function liveStats()
{
    $startDate = Carbon::now()->startOfMonth(); 
    $endDate = Carbon::now()->endOfMonth(); 

    
    $allDays = [];
   

    
    $userRegistrations = DB::table('users')
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->where('created_at', '>=', '2025-03-1 06:03:38')
        ->where('created_at', '<=', '2025-03-30 06:03:38')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get()
        ->keyBy('date')
        ->toArray();

    
    $userMessages = DB::table('message')
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->where('created_at', '>=', '2025-03-1 06:03:38')
        ->where('created_at', '<=', '2025-03-30 06:03:38')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get()
        ->keyBy('date')
        ->toArray();

    

    return response()->json([
        'userMessages' => array_values($userMessages),
        'userRegistrations' => array_values($userRegistrations)
        
    ]);
}



}
