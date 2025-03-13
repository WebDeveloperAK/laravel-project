<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'created_at']);
    
            return DataTables::of($users)
                ->addColumn('action', function ($user) {
                    return '<button class="btn btn-primary btn-sm editUser" 
                                    data-id="' . $user->id . '" 
                                    data-name="' . $user->name . '" 
                                    data-email="' . $user->email . '" 
                                    >
                                Edit
                            </button>';
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->format('Y-m-d');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('users');
    }

    public function update(Request $request, $id)
{
    
    $user = User::findOrFail($id);

    // Debugging: Check if role is coming from the request
    \Log::info('Updating user: ', [
        'id' => $id,
        'name' => $request->name,
        'email' => $request->email,
    ]);

    // Ensure role exists in the request and is valid
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
    ]);

    // Update user
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    return response()->json(['success' => 'User updated successfully.']);
}


}
