<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllChargesList extends Controller
{

    public function index()
    {
        $allCharges = \App\Models\AllChargesList::orderBy('name', 'asc')->get();
        return view('backend.all_charges.index',compact('allCharges'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        \App\Models\AllChargesList::create($validated);
        return back()->with('success','Charges Added Successfully');
    }












}
