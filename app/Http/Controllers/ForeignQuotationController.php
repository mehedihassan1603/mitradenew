<?php

namespace App\Http\Controllers;

use App\Models\ForeignQuotation;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\RequestQuotation;
use Illuminate\Http\Request;
use DB;

class ForeignQuotationController extends Controller
{
    // Show comparison page
    public function compare($requisitionId)
    {
        // Fetch requisition first
        $requisition = PurchaseRequisition::findOrFail($requisitionId);

        // Decode product_id and quantities
        $products = is_array($requisition->product_id)
            ? $requisition->product_id
            : (json_decode($requisition->product_id, true) ?? []);

        $quantities = is_array($requisition->quantities)
            ? $requisition->quantities
            : (json_decode($requisition->quantities, true) ?? []);

        // Fetch quotations for this requisition
        $quotations = RequestQuotation::with('items.product', 'supplier', 'exporter')
            ->where('purchase_requisition_id', $requisitionId)
            ->get();

        // Fetch product models for display
        $productModels = Product::whereIn('id', $products)->get()->keyBy('id');

        return view('backend.request_quotation.compare', compact(
            'requisition', 'products', 'quantities', 'quotations', 'productModels'
        ));
    }

    // Approve a quotation and create Purchase Order
    public function approve($id)
    {
        DB::transaction(function() use ($id) {
            $quotation = RequestQuotation::with('items')->findOrFail($id);

            // Approve this quotation
            $quotation->approved = true;
            $quotation->save();

            // Reject other quotations for the same requisition
            RequestQuotation::where('purchase_requisition_id', $quotation->purchase_requisition_id)
                ->where('id', '!=', $id)
                ->update(['approved' => false]);

            // Create Purchase Order
            $po = \App\Models\PurchaseOrder::create([
                'request_quotation_id' => $quotation->id,
                'supplier_id' => $quotation->supplier_id,
                'exporter_id' => $quotation->exporter_id,
                'total_amount' => $quotation->items->sum(fn($i) => $i->price * $i->quantity),
                'status' => 'pending',
            ]);

            // Create PO items
            foreach($quotation->items as $item) {
                $po->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);
            }
        });

        return back()->with('success', 'Quotation approved & Purchase Order created!');
    }
}
