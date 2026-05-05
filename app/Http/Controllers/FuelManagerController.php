<?php

namespace App\Http\Controllers;

use App\Models\FuelManager;
use App\Models\Staff;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FuelManagerController extends Controller
{
    public function index()
    {
        if(Auth::guard('manager')->check() && Auth::guard('manager')->user()->role=="station_manager"){
        $staff = FuelManager::with('station')->latest()->where('role','attendant')->where('station_id',Auth::guard('manager')->user()->station_id)->get();
        }else{
        $staff = FuelManager::with('station')->latest()->where('role','station_manager')->get();

        }
        $stations = Station::all(); 

        return view('fuelmanagers', compact('staff', 'stations'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'firstname'  => 'required',
            'lastname'   => 'required',
            'mobile'     => 'required',
            'role'    => 'required',
            'email'      => 'required|email|unique:fuel_workers,email',
            'password'   => 'required|min:4',
            'station_id' => 'required|exists:stations,id',
        ]);

        FuelManager::create([
            'firstname'  => $request->firstname,
            'lastname'   => $request->lastname,
            'mobile'     => $request->mobile,
            'email'      => $request->email,
            'role'       => $request->role, 
            'password'   => Hash::make($request->password),
            'station_id' => $request->station_id,
        ]);

        return back()->with('success', 'Staff added successfully');
    }

   
    public function update(Request $request, $id)
    {
        $staff = FuelManager::findOrFail($id);

        $request->validate([
            'firstname'  => 'required',
            'lastname'   => 'required',
            'mobile'     => 'required',
            'email'      => 'required|email|unique:staff,email,' . $id,
            'station_id' => 'required|exists:stations,id',
        ]);

        $data = [
            'firstname'  => $request->firstname,
            'lastname'   => $request->lastname,
            'mobile'     => $request->mobile,
            'email'      => $request->email,
            'station_id' => $request->station_id,
        ];

       
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return back()->with('success', 'Staff updated successfully');
    }
}