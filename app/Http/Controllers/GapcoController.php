<?php

namespace App\Http\Controllers;

use App\Models\Gapco;
use Illuminate\Http\Request;

class GapcoController extends Controller
{
    public function index()
    {
        $gapcos = Gapco::latest()->get();

        return view('organization', compact('gapcos'));
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
