<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function index(Request $request){
        $ip = "103.86.16.203";
        $data = file_get_contents("http://ip-api.com/json/{$ip}");
        $location = json_decode($data);
        $country = $location->country;
        $city = $location->city;
        echo "User is from $city, $country";
    }

    public function store(Request $request){
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
    
            // Get the user's real IP address
            $ip = $request->header('X-Forwarded-For') ?? $request->ip(); 
    
            // If multiple IPs are found, get the first one
            if (strpos($ip, ',') !== false) {
                $ip = explode(',', $ip)[0];
            }
    
            // Update the IP address in the database
            DB::table('users')->where('id', Auth::user()->id)->update(['ip_address' => $ip]);
    
            return redirect()->intended('dashboard')->with('success', 'Login successful!');
        }
    
        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }
}
