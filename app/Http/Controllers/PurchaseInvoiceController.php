<?php

namespace App\Http\Controllers;

use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceAttachment;
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
        ]);

        $po = PurchaseOrder::findOrFail($request->purchase_order_id);

        // Calculate total: sum of PO total + transportation
        $totalAmount = $po->total_amount + ($request->transportation_cost ?? 0);

        $invoice = PurchaseInvoice::create([
            'purchase_order_id'    => $po->id,
            'pi_number'            => $request->pi_number,
            'pi_date'              => $request->pi_date,
            'transportation_cost'  => $request->transportation_cost ?? 0,
            'total_amount'         => $totalAmount,
            'notes'                => $request->notes,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/pi'), $filename);

                PurchaseInvoiceAttachment::create([
                    'purchase_invoice_id' => $invoice->id,
                    'file_path'           => 'uploads/pi/' . $filename,
                ]);
            }
        }

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
        $invoice = PurchaseInvoice::with(['order.supplier','order.exporter','attachments','order.items.product'])->findOrFail($id);
        // dd($invoice);
        return view('backend.purchase_invoice.show', compact('invoice'));
    }

    public function received($id)
    {
        $invoice = PurchaseInvoice::with(['order.supplier','order.exporter','attachments','order.items.product'])->findOrFail($id);
// dd($invoice);
        return view('backend.purchase_invoice.received', compact('invoice'));
    }





}
