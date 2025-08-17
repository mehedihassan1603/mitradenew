<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceReceived;
use App\Models\PurchaseInvoiceReceivedItem;
use Illuminate\Http\Request;

class InvoiceReceivedController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'invoice_id' => 'required|exists:purchase_invoices,id',
            'received_qty' => 'required|array',
        ]);

        // Save main record
        $received = PurchaseInvoiceReceived::create([
            'purchase_invoice_id' => $request->invoice_id,
            'received_notes' => $request->received_notes,
            'custom_duty' => $request->custom_duty ?? 0,
            'vat' => $request->vat ?? 0,
            'supplementary_duty' => $request->supplementary_duty ?? 0,
            'thc' => $request->thc ?? 0,
            'container_handling' => $request->container_handling ?? 0,
            'custom_clearance' => $request->custom_clearance ?? 0,
            'documentation_charges' => $request->documentation_charges ?? 0,
            'truck_cost' => $request->truck_cost ?? 0,
            'warehouse_receiving' => $request->warehouse_receiving ?? 0,
            'inspection_qc' => $request->inspection_qc ?? 0,
            'packaging_labeling' => $request->packaging_labeling ?? 0,
            'fuel_toll' => $request->fuel_toll ?? 0,
            'status' => 0, // pending
        ]);

        // Save item-level received qty
        foreach ($request->received_qty as $itemId => $qty) {
            PurchaseInvoiceReceivedItem::create([
                'received_id' => $received->id,
                'item_id' => $itemId,
                'received_qty' => $qty,
            ]);
        }

        return redirect()->route('purchase.invoice.received.index')
            ->with('success', 'Purchase Invoice Received saved successfully.');
    }

    public function index()
    {
        $receivedList = PurchaseInvoiceReceived::with('invoice.order.supplier')->orderBy('id','desc')->get();
        return view('backend.purchase_invoice.received_index', compact('receivedList'));
    }



    public function approve($id)
{
    $received = PurchaseInvoiceReceived::with('items')->findOrFail($id);
    dd($received);

    if ($received->status == 1) {
        return redirect()->back()->with('info', 'Already approved.');
    }

    // Total number of received items
    $totalItems = $received->items->count();

    if ($totalItems == 0) {
        return redirect()->back()->with('error', 'No items found to approve.');
    }

    // Sum of extra charges
    $extraCharges = $received->custom_duty
        + $received->vat
        + $received->supplementary_duty
        + $received->thc
        + $received->container_handling
        + $received->custom_clearance
        + $received->documentation_charges
        + $received->truck_cost
        + $received->warehouse_receiving
        + $received->inspection_qc
        + $received->packaging_labeling
        + $received->fuel_toll;

    // Distribute extra charges equally per item
    $extraPerItem = $extraCharges / $totalItems;

    foreach ($received->items as $item) {
        $baseCost = $item->received_qty * $item->received()->price ?? 0; // if you have product price
        $item->actual_cost = $baseCost + $extraPerItem;
        $item->save();
    }

    // Mark received as approved
    $received->status = 1;
    $received->save();

    return redirect()->back()->with('success', 'Purchase Invoice Received approved successfully.');
}


}
