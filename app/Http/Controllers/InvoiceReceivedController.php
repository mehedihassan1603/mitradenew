<?php

namespace App\Http\Controllers;

use App\Models\LC;
use App\Models\Product_Warehouse;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceReceived;
use App\Models\PurchaseInvoiceReceivedCharge;
use App\Models\PurchaseInvoiceReceivedItem;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InvoiceReceivedController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'invoice_id'   => 'required|exists:purchase_invoices,id',
        'received_qty' => 'required|array',
        'bl_number'    => 'required|string',
        'lc_id'        => 'required|exists:lcs,id',
    ]);

    $invoice = PurchaseInvoice::with('items')->findOrFail($request->invoice_id);

    $received = PurchaseInvoiceReceived::create([
        'purchase_invoice_id' => $request->invoice_id,
        'bl_number'           => $request->bl_number,
        'lc_id'               => $request->lc_id,
        'transportation_cost' => $request->transportation_cost ?? 0,
        'received_notes'      => $request->received_notes,
    ]);

    foreach ($request->received_qty as $itemId => $qty) {
        $item = $invoice->items->where('id', $itemId)->first();
        if ($item) {
            $unitPrice = $item->price ?? 0;
            $baseCost = $qty * $unitPrice;

            // Get HS VAT percentage from product's HS code relation
            $hsRate = $item->product->hs->value ?? 0;
            $hsVat = ($baseCost * $hsRate) / 100;
            $actualCost = $baseCost + $hsVat;

            PurchaseInvoiceReceivedItem::create([
                'received_id'  => $received->id,
                'item_id'      => $itemId,
                'product_id'   => $item->product_id,
                'received_qty' => $qty,
                'unit_price'   => $unitPrice,
                'hs_vat_rate'  => $hsRate,        // store HS VAT rate (%)
                'unit_price_with_vat'  => $actualCost,    // store total cost (base + VAT)
            ]);
        }
    }


    // Save selected charges dynamically
    if ($request->charge_id && $request->charge_amount) {
        foreach ($request->charge_id as $index => $chargeId) {
            PurchaseInvoiceReceivedCharge::create([
                'received_id' => $received->id,
                'charge_id'   => $chargeId,
                'amount'      => $request->charge_amount[$index] ?? 0,
            ]);
        }
    }

    return redirect()->route('purchase.invoice.received.index')->with('success', 'Purchase Invoice Received saved successfully.');
}

    public function index()
    {
        $receivedList = PurchaseInvoiceReceived::with('invoice.order.supplier')->orderBy('id','desc')->get();
        $warehouses = Warehouse::all();
        return view('backend.purchase_invoice.received_index', compact('receivedList', 'warehouses'));
    }




    public function approve(Request $request, $id)
{
    $request->validate([
        'warehouse_id' => 'required',
    ]);

    $received = PurchaseInvoiceReceived::with(['items','charges'])->findOrFail($id);

    if ($received->status == 1) {
        return redirect()->back()->with('info', 'Already approved.');
    }

    $totalItems = $received->items->count();
    if ($totalItems == 0) {
        return redirect()->back()->with('error', 'No items found to approve.');
    }

    // Calculate extra charges
    $extraCharges = $received->transportation_cost + ($received->charges->sum('amount') ?? 0);

    // Equal share per item
    $extraPerItem = $totalItems > 0 ? $extraCharges / $totalItems : 0;

    foreach ($received->items as $item) {
        $baseCost = $item->received_qty * $item->unit_price;
        $item->actual_cost = $baseCost + $extraPerItem;
        $item->save();

        // Update stock
        $productWarehouse = Product_Warehouse::firstOrNew([
            'product_id' => $item->product_id,
            'warehouse_id' => $request->warehouse_id,
        ]);
        $productWarehouse->qty = ($productWarehouse->qty ?? 0) + $item->received_qty;
        $productWarehouse->save();
    }

    $received->status = 1;
    $received->save();

    return redirect()->route('purchase.invoice.received.index')->with('success', 'Approved and stock updated.');
}



public function show($id)
{
    $received = PurchaseInvoiceReceived::with(['items.product','invoice.order.supplier','invoice.order.exporter'])->findOrFail($id);
    $warehouses = Warehouse::all();
    return view('backend.purchase_invoice.received_show', compact('received', 'warehouses'));
}


}
