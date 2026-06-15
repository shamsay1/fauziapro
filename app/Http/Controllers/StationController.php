<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::all();
        $noteCount = Notification::where("read_by","admin")->count();
        $notes = Notification::where("read_by","admin")->get();
        return view('stationinfo', compact('stations','noteCount','notes'));
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
