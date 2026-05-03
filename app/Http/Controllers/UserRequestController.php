<?php

namespace App\Http\Controllers;

use App\Models\SystemUser;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRequestController extends Controller
{
    public function index()
    {
        $requests = UserRequest::with('user')->latest()->get();
        $users = SystemUser::all();

        return view('userRequest', compact('requests', 'users'));
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            'request_amount' => 'required',
            'number_of_litre' => 'required'
            
        ]);

        UserRequest::create([
            'request_amount' => $request->request_amount,
            'number_of_litre' => $request->number_of_litre,
            'requested_by'   => Auth::guard('web')->user()->id,
            'status'         => 'pending',
        ]);

        return back()->with('success', 'Request created successfully');
    }

   
    public function update(Request $request, $id)
    {
        $req = UserRequest::findOrFail($id);

        $req->update([
            'request_amount' => $request->request_amount,
            'status'         => $request->status,
        ]);

        return back()->with('success', 'Request updated successfully');
    }
}
