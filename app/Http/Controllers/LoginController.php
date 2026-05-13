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

        return redirect()->route('dashboard'); 
    }

    
    // if (Auth::guard('manager')->attempt($credentials)) {

    //     $user = Auth::guard('manager')->user();
    //     if($user->role=="attendant"){
    //         return redirect()->route("verify");
    //     }else{
    //         return redirect()->route("dashboard");
    //     }
    // }
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
