<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::where("organization_id",Auth::guard('web')->user()->organization_id)->get();
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

        Station::create([
            'station_name' => $request->station_name,
            'location' => $request->location,
            'organization_id' => Auth::guard('web')->user()->organization_id,
        ]);

        return back()->with('success', 'Station added successfully');
    }
    public function update(Request $request, $id)
    {
        $station = Station::findOrFail($id);

        $station->update($request->all());

        return back()->with('success', 'Station updated successfully');
    }
    public function destroy($id)
{
    $station = Station::findOrFail($id);

    $station->delete();

    return redirect()->back()->with('success', 'Station deleted successfully');
}
}
