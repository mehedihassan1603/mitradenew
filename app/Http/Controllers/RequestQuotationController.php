<?php

namespace App\Http\Controllers;

use App\Models\ForeignQuotation;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\RequestQuotation;
use App\Models\Supplier;
use Illuminate\Http\Request;

class RequestQuotationController extends Controller
{
    public function index() {
        $requisitions = PurchaseRequisition::orderBy('id', 'desc')->get();
        return view('backend.request_quotation.index', compact('requisitions'));
    }

    public function create($requisitionId) {
        $requisition = PurchaseRequisition::findOrFail($requisitionId);
        // $products = json_decode($requisition->product_id, true) ?? [];
        // $quantities = json_decode($requisition->quantities, true) ?? [];

        $products = is_array($requisition->product_id)
    ? $requisition->product_id
    : (json_decode($requisition->product_id, true) ?? []);

$quantities = is_array($requisition->quantities)
    ? $requisition->quantities
    : (json_decode($requisition->quantities, true) ?? []);

        $suppliers = Supplier::all(); // your supplier table

        return view('backend.request_quotation.create', compact(
            'requisition', 'products', 'quantities', 'suppliers'
        ));
    }

    public function store(Request $request, $requisitionId) {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'prices' => 'required|array',
            'prices.*' => 'numeric|min:0',
        ]);

        $requisition = PurchaseRequisition::findOrFail($requisitionId);

        RequestQuotation::create([
            'purchase_requisition_id' => $requisition->id,
            'supplier_id' => $request->supplier_id,
            'product_ids' => $requisition->product_id,
            'quantities' => $requisition->quantities,
            'prices' => json_encode($request->prices),
        ]);

        return redirect()->route('request.quotation.index')
            ->with('success', 'Supplier Quotation Added Successfully!');
    }

    public function compare($requisitionId)
    {
        $requisition = PurchaseRequisition::findOrFail($requisitionId);

        $products = is_array($requisition->product_id)
            ? $requisition->product_id
            : (json_decode($requisition->product_id, true) ?? []);

        $quantities = is_array($requisition->quantities)
            ? $requisition->quantities
            : (json_decode($requisition->quantities, true) ?? []);


        $quotations = RequestQuotation::with('supplier')
            ->where('purchase_requisition_id', $requisitionId)
            ->get();


        // Build product models for display
        $productModels = Product::whereIn('id', $products)->get()->keyBy('id');

        return view('backend.request_quotation.compare', compact(
            'requisition', 'products', 'quantities', 'quotations', 'productModels'
        ));
    }


//     public function approve($requisitionId, $quoteId)
// {
//     $requisition = PurchaseRequisition::findOrFail($requisitionId);
//     $quotation   = RequestQuotation::with('supplier')->findOrFail($quoteId);

//     $products = is_array($requisition->product_id)
//         ? $requisition->product_id
//         : (json_decode($requisition->product_id, true) ?? []);

//     $quantities = is_array($requisition->quantities)
//         ? $requisition->quantities
//         : (json_decode($requisition->quantities, true) ?? []);

//     $prices = json_decode($quotation->prices, true) ?? [];

//     $productModels = Product::whereIn('id', $products)->get()->keyBy('id');

//     $exporters = Supplier::where('supplier_type', 'Exporter')->get(); // assuming you have a flag for exporters

//     return view('backend.purchase_order.create', compact(
//         'requisition', 'quotation', 'products', 'quantities', 'prices', 'productModels', 'exporters'
//     ));
// }

public function approve($requisitionId, $quoteId)
{
    $requisition = PurchaseRequisition::findOrFail($requisitionId);
    $quotation   = RequestQuotation::with('supplier')->findOrFail($quoteId);

    // Mark this quotation as approved
    $quotation->status = 'approved';
    $quotation->save();

    // (Optional) Also update requisition status
    $requisition->status = 'approved';
    $requisition->save();

    return redirect()->route('request.quotation.approved.list')
        ->with('success', 'Quotation approved successfully.');
}
public function approvedList()
{
    $approvedQuotations = RequestQuotation::with(['supplier', 'requisition'])
        ->where('status', 'approved')
        ->orderBy('id', 'desc')
        ->get();

    return view('backend.request_quotation.approved_list', compact('approvedQuotations'));
}







}
