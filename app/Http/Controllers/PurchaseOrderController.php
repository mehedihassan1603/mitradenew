<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequisition;
use App\Models\RequestQuotation;
use App\Models\Supplier;
use Carbon\Carbon;
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

public function create($requisitionId, $quoteId)
{
    $requisition = PurchaseRequisition::findOrFail($requisitionId);
    $quotation   = RequestQuotation::with('supplier')->findOrFail($quoteId);

    // Handle products
    $products = is_array($requisition->product_id)
        ? $requisition->product_id
        : (json_decode($requisition->product_id, true) ?? []);

    // Handle quantities
    $quantities = is_array($requisition->quantities)
        ? $requisition->quantities
        : (json_decode($requisition->quantities, true) ?? []);

    // Handle prices
    $prices = is_array($quotation->prices)
        ? $quotation->prices
        : (json_decode($quotation->prices, true) ?? []);

    $productModels = Product::whereIn('id', $products)->get()->keyBy('id');
    $exporters = Supplier::where('supplier_type', 'Exporter')->get();

    return view('backend.purchase_order.create', compact(
        'requisition', 'quotation', 'products', 'quantities', 'prices', 'productModels', 'exporters'
    ));
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
            'po_id'          => 'required|string',
            'supplier_id'    => 'required|integer',
            'exporter_id'    => 'required|integer',
            'product_ids'    => 'required|array',
            'quantities'     => 'required|array',
            'prices'         => 'required|array',
            'date'           => 'nullable',
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
            'po_id'                   => $request->po_id,
            'supplier_id'             => $request->supplier_id,
            'exporter_id'             => $request->exporter_id,
            'order_date'              => $request->date ?? Carbon::now()->format('Y-m-d'),
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

        return redirect()->route('purchase.order.index')
            ->with('success', 'Purchase Order created successfully!');
    }
}
