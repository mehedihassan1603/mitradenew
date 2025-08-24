<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class AllChargesList extends Controller
{

//        public function index()
//        {
//            $allCharges = \App\Models\AllChargesList::orderBy('name', 'asc')->get();
//            return view('backend.all_charges.index',compact('allCharges'));
//        }
//
//        public function store(Request $request)
//        {
//            $validated = $request->validate([
//                'name' => 'required|string|max:255',
//            ]);
//            \App\Models\AllChargesList::create($validated);
//            return back()->with('success','Charges Added Successfully');
//        }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = \App\Models\AllChargesList::orderBy('id', 'desc');
//            <button class="btn btn-sm btn-primary" data-id="'.$row->id.'" id="editCountryBtn">Edit</button>
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '<div  class="btn-group">

                                <button class="btn btn-sm btn-danger" data-id="'.$row->id.'" id="deleteCountryBtn">Delete</button>
                            </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        // ajax call na hole
        return view('backend.all_charges.index');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:all_charges_lists,name',
            ]);

            $charges = \App\Models\AllChargesList::create($data);
            return response()->json(['status' =>'success' , 'message' => 'Data saved successfully.', 'data' => $charges]);

        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error occurred.', 'details' => $e->getMessage()], 500);
        }
    }


    public function edit(Request $request)
    {
        $id = $request->id;
        $charge = \App\Models\AllChargesList::find($id);
        return response()->json(['data' => $charge]);
    }

    public function update(Request $request)
    {
        try {
            $id = $request->id;
            $charge = \App\Models\AllChargesList::findOrFail($id);

            $data = $request->validate([
                'name'  => 'required|string|max:255|unique:all_charges_lists,name,' . $charge->id,
            ]);

            $charge->update($data);

            return response()->json(['status'=>'success','message'=>'Country updated successfully.','data' => $charge]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Unexpected error occurred.',
                'details' => $e->getMessage()
            ], 500);
        }
    }



    public function delete(Request $request)
    {
        try {

            $country = \App\Models\AllChargesList::findOrFail($request->id);
            $country->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error occurred.','details' => $e->getMessage(),], 500);
        }
    }











}
