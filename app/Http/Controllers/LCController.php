<?php

namespace App\Http\Controllers;

use App\Models\LC;
use App\Models\LCDocument;
use Illuminate\Http\Request;

class LCController extends Controller
{
    public function index()
    {
        $lcs = LC::with('supplier', 'exporter', 'bank')->get();
        return view('backend.lc.index', compact('lcs'));
    }

    public function create()
    {
        $suppliers = \App\Models\Supplier::all();
        $exporters = \App\Models\Supplier::where('supplier_type', 'Exporter')->get();
        $banks = \App\Models\Bank::all();

        return view('backend.lc.create', compact('suppliers', 'exporters', 'banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lc_number' => 'required|unique:lcs',
            'lc_type' => 'required',
            'lc_amount' => 'required|numeric',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date',
            'bank_id' => 'required'
        ]);

        // Create LC
        $lc = LC::create($request->only([
            'lc_number', 'lc_type', 'supplier_id', 'exporter_id', 'bank_id',
            'lc_amount', 'status', 'issue_date', 'expiry_date', 'remarks',
            'application_date', 'approval_date'
        ]));

        // Save documents
        //        if ($request->hasFile('documents')) {
        //            foreach ($request->file('documents') as $index => $file) {
        //                $path = $file->store('lc_documents', 'public');
        //                LCDocument::create([
        //                    'lc_id' => $lc->id,
        //                    'document_type' => $request->document_name[$index] ?? 'Other',
        //                    'file_path' => $path
        //                ]);
        //            }
        //        }

        // Save documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $document) {

                $documentFile = uniqid().'_'.time().'.'.$document->getClientOriginalExtension();
                $document->move(public_path('lc-documents/'), $documentFile);
                LCDocument::create([
                    'lc_id'         => $lc->id,
                    'document_type' => $request->document_name[$index] ?? 'Null',
                    'file_path'     => 'lc-documents/' . $documentFile,
                ]);
            }
        }

        // Save expenses
        if ($request->filled('expense_type')) {
            foreach ($request->expense_type as $index => $type) {
                if ($type && isset($request->expense_amount[$index])) {
                    \DB::table('lc_expenses')->insert([
                        'lc_id' => $lc->id,
                        'expense_type' => $type,
                        'amount' => $request->expense_amount[$index],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        return redirect()->route('lcs.index')->with('success', 'LC created successfully!');
    }


    public function show(LC $lc , $id)
    {
//        $lc->load('documents', 'adjustments', 'supplier', 'exporter', 'bank');

        $lc =  LC::find($id);
//        dd($lc);
        return view('backend.lc.show', compact('lc'));
    }

    public function edit(LC $lc)
    {
        $suppliers = \App\Models\Supplier::all();
        $exporters = \App\Models\Exporter::all();
        $banks = \App\Models\Bank::all();

        return view('backend.lc.edit', compact('lc', 'suppliers', 'exporters', 'banks'));
    }

    public function update(Request $request, LC $lc)
    {
        $request->validate([
            'lc_number' => 'required|unique:lcs,lc_number,' . $lc->id,
            'lc_type' => 'required',
            'lc_amount' => 'required|numeric',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date',
            'bank_id' => 'required'
        ]);

        $lc->update($request->all());
        return redirect()->route('lcs.index')->with('success', 'LC updated successfully!');
    }

    public function destroy(LC $lc)
    {
        $lc->delete();
        return redirect()->route('lcs.index')->with('success', 'LC deleted successfully!');
    }
}
