<?php

namespace App\Http\Controllers;

use App\Models\ForeignQuotation;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\RequestQuotation;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'requisition_id' => 'required|exists:purchase_requisition,id',
        'quotation_id'   => 'required|exists:request_quotations,id',
        'supplier_id'    => 'required|exists:suppliers,id',
        'exporter_id'    => 'required|exists:suppliers,id',
        'product_ids'    => 'required|array',
        'quantities'     => 'required|array',
        'prices'         => 'required|array',
    ]);

    $purchaseOrder = PurchaseOrder::create([
        'purchase_requisition_id' => $request->requisition_id,
        'request_quotation_id'    => $request->quotation_id,
        'supplier_id'             => $request->supplier_id,
        'exporter_id'             => $request->exporter_id,
        'order_date'              => now(),
        'status'                  => 'draft',
        'total_amount'            => collect($request->quantities)->map(function($qty, $i) use ($request) {
                                        return $qty * $request->prices[$i];
                                    })->sum(),
    ]);

    foreach ($request->product_ids as $i => $productId) {
        $purchaseOrder->items()->create([
            'product_id' => $productId,
            'quantity'   => $request->quantities[$i],
            'price'      => $request->prices[$i],
        ]);
    }

    return redirect()->route('request.quotation.index')
        ->with('success', 'Purchase Order created successfully!');
}


}
