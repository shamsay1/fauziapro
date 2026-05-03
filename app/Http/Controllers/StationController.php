<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::all();
        return view('stationinfo', compact('stations'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'station_name' => 'required',
            'location' => 'required',
        ]);

        Station::create($request->all());

        return back()->with('success', 'Station added successfully');
    }
    public function update(Request $request, $id)
    {
        $station = Station::findOrFail($id);

        $station->update($request->all());

        return back()->with('success', 'Station updated successfully');
    }
}
