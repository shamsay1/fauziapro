<?php

namespace App\Http\Controllers;

use App\Models\Gapco;
use App\Models\Notification;
use Illuminate\Http\Request;

class GapcoController extends Controller
{
    public function index()
    {
        $gapcos = Gapco::where('id','>',1)->latest()->get();
        $noteCount = Notification::where("read_by","admin")->count();
        $notes = Notification::where("read_by","admin")->get();
        return view('organization', compact('gapcos','noteCount','notes'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'type'         => 'required',
        ]);

        Gapco::create([
            'company_name' => $request->company_name,
            'type'         => $request->type,
        ]);

        return back()->with('success', 'Company added successfully');
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required',
            'type'         => 'required',
        ]);

        $gapco = Gapco::findOrFail($id);

        $gapco->update([
            'company_name' => $request->company_name,
            'type'         => $request->type,
        ]);

        return back()->with('success', 'Company updated successfully');
    }
}
