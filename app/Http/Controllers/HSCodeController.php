<?php

namespace App\Http\Controllers;

use App\Models\HSCode;
use Illuminate\Http\Request;

class HSCodeController extends Controller
{
    public function index()
    {
        $hscodes = HSCode::all();
        return view('backend.hs_code.index', compact('hscodes'));

    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'value' => 'string|max:255',
                'status' => 'string|max:255',
            ]);

            $hscode = HSCode::create($data);
            return response()->json(['status' =>'success' , 'message' => 'Data saved successfully.', 'data' => $hscode]);

        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }


    public function edit()
    {
        return view('backend.hs_code.edit');
    }







}
