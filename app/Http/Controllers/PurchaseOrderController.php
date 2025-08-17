<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
{
    // Load all POs with supplier and exporter info
    $purchaseOrders = PurchaseOrder::with(['supplier', 'exporter', 'items.product'])
                        ->orderBy('id', 'desc')
                        ->get();

    return view('backend.purchase_order.index', compact('purchaseOrders'));
}
public function show($id)
{
    $po = PurchaseOrder::with(['supplier', 'exporter', 'items.product'])
            ->findOrFail($id);

    return view('backend.purchase_order.show', compact('po'));
}


    public function store(Request $request)
    {
        $request->validate([
            'requisition_id' => 'required|integer',
            'quotation_id'   => 'required|integer',
            'supplier_id'    => 'required|integer',
            'exporter_id'    => 'required|integer',
            'product_ids'    => 'required|array',
            'quantities'     => 'required|array',
            'prices'         => 'required|array',
        ]);

        // Calculate total
        $total = 0;
        foreach ($request->product_ids as $i => $pid) {
            $qty   = $request->quantities[$i] ?? 0;
            $price = $request->prices[$i] ?? 0;
            $total += $qty * $price;
        }

        // Create Purchase Order
        $po = PurchaseOrder::create([
            'purchase_requisition_id' => $request->requisition_id,
            'request_quotation_id'    => $request->quotation_id,
            'supplier_id'             => $request->supplier_id,
            'exporter_id'             => $request->exporter_id,
            'order_date'              => now(),
            'status'                  => 'draft', // you can use pending/approved/etc
            'total_amount'            => $total,
        ]);

        // Create items
        foreach ($request->product_ids as $i => $pid) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id'        => $pid,
                'quantity'          => $request->quantities[$i] ?? 0,
                'price'             => $request->prices[$i] ?? 0,
            ]);
        }

        return redirect()->route('request.quotation.index')
            ->with('success', 'Purchase Order created successfully!');
    }
}
