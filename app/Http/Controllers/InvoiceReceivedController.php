<?php

namespace App\Http\Controllers;

use App\Models\Product_Warehouse;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceReceived;
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
    ]);

    // Get invoice with its items
    $invoice = PurchaseInvoice::with('items')->findOrFail($request->invoice_id);

    // Save main record
    $received = PurchaseInvoiceReceived::create([
        'purchase_invoice_id'   => $request->invoice_id,
        'received_notes'        => $request->received_notes,
        'custom_duty'           => $request->custom_duty ?? 0,
        'vat'                   => $request->vat ?? 0,
        'supplementary_duty'    => $request->supplementary_duty ?? 0,
        'thc'                   => $request->thc ?? 0,
        'container_handling'    => $request->container_handling ?? 0,
        'custom_clearance'      => $request->custom_clearance ?? 0,
        'documentation_charges' => $request->documentation_charges ?? 0,
        'truck_cost'            => $request->truck_cost ?? 0,
        'warehouse_receiving'   => $request->warehouse_receiving ?? 0,
        'inspection_qc'         => $request->inspection_qc ?? 0,
        'packaging_labeling'    => $request->packaging_labeling ?? 0,
        'fuel_toll'             => $request->fuel_toll ?? 0,
        'status'                => 0,
    ]);

    // Save item-level received qty & price
    foreach ($request->received_qty as $itemId => $qty) {
        $item = $invoice->items->where('id', $itemId)->first();

        if ($item) {
            PurchaseInvoiceReceivedItem::create([
                'received_id'  => $received->id,
                'item_id'      => $itemId,
                'product_id'   => $item->product_id,
                'received_qty' => $qty,
                'unit_price'   => $item->price ?? 0,
            ]);
        }
    }

    return redirect()->route('purchase.invoice.received.index')
        ->with('success', 'Purchase Invoice Received saved successfully.');
}



    public function index()
    {
        $receivedList = PurchaseInvoiceReceived::with('invoice.order.supplier')->orderBy('id','desc')->get();
        $warehouses = Warehouse::all();
        return view('backend.purchase_invoice.received_index', compact('receivedList', 'warehouses'));
    }




    public function approve(Request $request,$id)
{
    // dd($request->all(), $id);
    $request->validate([
        'warehouse_id' => 'required',
    ]);
    $received = PurchaseInvoiceReceived::with('items', 'invoice.order.items')->findOrFail($id);

    // dd($received);

    if ($received->status == 1) {
        return redirect()->back()->with('info', 'Already approved.');
    }

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

    // Equal share per item
    $extraPerItem = $extraCharges / $totalItems;

    foreach ($received->items as $item) {
        $baseCost = ($item->received_qty * $item->unit_price);
        $item->actual_cost = $baseCost + $extraPerItem;
        $item->save();

        // ✅ Update product warehouse stock
        $warehouseId = $request->warehouse_id; // change if you have dynamic warehouse selection
        $productWarehouse = Product_Warehouse::where('product_id', $item->product_id)
            ->where('warehouse_id', $warehouseId)
            ->first();

        if ($productWarehouse) {
            // update existing stock
            $productWarehouse->qty += $item->received_qty;
            $productWarehouse->save();
        } else {
            // create new stock record if not exist
            Product_Warehouse::create([
                'product_id'   => $item->product_id,
                'warehouse_id' => $warehouseId,
                'qty'          => $item->received_qty,
            ]);
        }
    }

    // Mark received as approved
    $received->status = 1;
    $received->save();

    return redirect()->back()->with('success', 'Purchase Invoice Received approved successfully and stock updated.');
}




}
