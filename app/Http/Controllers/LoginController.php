<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin(){
        return view("login");
    }
    public function login(Request $request)
{
    $request->validate([
        "email" => "required|email",
        "password" => "required"
    ]);

    $credentials = $request->only("email", "password");

    
    if (Auth::guard('web')->attempt($credentials)) {

        $user = Auth::guard('web')->user();
        if($user->status == "Active"){
        return redirect()->route('dashboard'); 
        }
        if($user->status == "blocked"){
            return back()->with("error","Your account is blocked!");
        }
        

    }
    return back()->with('error', 'Invalid email or password');
}
    public function logout(Request $request)
{
    Auth::guard('web')->logout();
    Auth::guard('manager')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login1'); 
}



}
