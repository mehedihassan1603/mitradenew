<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseRequisition;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseRequisitionController extends Controller
{
    public function index()
    {
        $requisitions = PurchaseRequisition::orderBy('id', 'desc')->get();
        return view('backend.purchase_requisition.index', compact('requisitions'));
    }

    public function create()
    {
        $products = Product::select('id', 'name', 'code')->get();
        return view('backend.purchase_requisition.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'ref_id' => 'required|unique:purchase_requisition,ref_id',
            'product_name' => 'required|array',
            'product_name.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:1'
        ]);

        PurchaseRequisition::create([
            'date' => $request->date ?? Carbon::now()->format('Y-m-d'), // default today
            'ref_id' => $request->ref_id,
            'product_id' => array_values($request->product_name),
            'quantities' => array_values($request->quantities),
        ]);

        return redirect()->route('purchase.requisition.index')
            ->with('success', 'Purchase Requisition Created Successfully!');
    }

    public function print($id)
    {
        // Fetch the requisition
        $req = \App\Models\PurchaseRequisition::where('id', $id)->firstOrFail();

        // Decode JSON safely
        $products = is_array($req->product_id) ? $req->product_id : json_decode($req->product_id, true) ?? [];
        $quantities = is_array($req->quantities) ? $req->quantities : json_decode($req->quantities, true) ?? [];

        // Fetch all products at once (avoid N+1 queries)
        $productModels = \App\Models\Product::whereIn('id', $products)->get()->keyBy('id');

        return view('backend.purchase_requisition.print', compact('req', 'products', 'quantities', 'productModels'));
    }


    // public function edit($id)
    // {
    //     $purchaseRequisition = PurchaseRequisition::find($id);
    //     $products = Product::select('id', 'name', 'code')->get();
    //     return view('backend.purchase_requisition.edit',compact('purchaseRequisition','products'));

    // }

    public function edit($id)
{
    $purchaseRequisition = PurchaseRequisition::find($id);
    $products = Product::select('id', 'name', 'code')->get();

    // product_id and quantities are already arrays
    $productIds = $purchaseRequisition->product_id ?? [];
    $quantities = $purchaseRequisition->quantities ?? [];

    // Combine IDs with quantities (assuming they are stored in the same order)
    $productQuantities = array_combine($productIds, $quantities);

    return view('backend.purchase_requisition.edit', compact('purchaseRequisition', 'products', 'productQuantities'));
}


    public function update(Request $request, $id)
    {
        $purchaseRequisition = PurchaseRequisition::findOrFail($id);

        $request->validate([
            'date' => 'nullable|date', // nullable দিলে empty থাকলেও চলবে
            'ref_id' => 'required|string',
            'product_name' => 'required|array',
            'product_name.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:1'
        ]);

        $purchaseRequisition->update([
            'date' => $request->date ?? $purchaseRequisition->date,
            'ref_id' => $request->ref_id,
            'product_id' => array_values($request->product_name),
            'quantities' => array_values($request->quantities),
        ]);

        return redirect()->route('purchase.requisition.index')
            ->with('success', 'Purchase Requisition Updated Successfully!');
    }







}
