<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceAttachment;
use App\Models\PurchaseInvoiceItem;
use App\Models\PurchaseInvoiceReceivedItem;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PurchaseInvoiceController extends Controller
{
    public function create($poId)
    {
        $po = PurchaseOrder::with(['items.product'])->findOrFail($poId);

        return view('backend.purchase_invoice.create', compact('po'));
    }

    public function store(Request $request)
{
    $request->validate([
        'purchase_order_id' => 'required|exists:purchase_orders,id',
        'pi_number'         => 'required|unique:purchase_invoices,pi_number',
        'pi_date'           => 'required|date',
        'transportation_cost'=> 'nullable|numeric|min:0',
        'notes'             => 'nullable|string',
        'attachments.*'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        'items'             => 'required|array',
        'items.*.product_id'=> 'required|exists:products,id',
        'items.*.quantity'  => 'required|numeric|min:1',
        'items.*.price'     => 'required|numeric|min:0',
    ]);

    $po = PurchaseOrder::findOrFail($request->purchase_order_id);

    // Recalculate total
    $totalItemsAmount = 0;
    foreach ($request->items as $item) {
        $totalItemsAmount += $item['quantity'] * $item['price'];
    }
    $totalAmount = $totalItemsAmount + ($request->transportation_cost ?? 0);

    $invoice = PurchaseInvoice::create([
        'purchase_order_id'    => $po->id,
        'pi_number'            => $request->pi_number,
        'pi_date'              => $request->pi_date,
        'transportation_cost'  => $request->transportation_cost ?? 0,
        'total_amount'         => $totalAmount,
        'notes'                => $request->notes,
    ]);

    // Save invoice items
    foreach ($request->items as $item) {
        PurchaseInvoiceItem::create([
            'purchase_invoice_id' => $invoice->id,
            'product_id'          => $item['product_id'],
            'quantity'            => $item['quantity'],
            'price'               => $item['price'],
            'line_total'          => $item['quantity'] * $item['price'],
        ]);
    }

    // Handle attachments (your existing code)

    return redirect()->route('purchase.invoice.index')
        ->with('success', 'Purchase Invoice created successfully!');
}


    public function index()
    {
        $invoices = PurchaseInvoice::with(['order.supplier', 'order.exporter'])->orderBy('id','desc')->get();
        return view('backend.purchase_invoice.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = PurchaseInvoice::with([
            'order.supplier',
            'order.exporter',
            'attachments',
            'items.product' // 👈 load invoice items instead of order items
        ])->findOrFail($id);

        return view('backend.purchase_invoice.show', compact('invoice'));
    }


    public function received($id)
{
    $invoice = PurchaseInvoice::with([
        'order.supplier',
        'order.exporter',
        'attachments',
        'items.product' // 👈 use invoice items instead of order items
    ])->findOrFail($id);

    return view('backend.purchase_invoice.received', compact('invoice'));
}






}
