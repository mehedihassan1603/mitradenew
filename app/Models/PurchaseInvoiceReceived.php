<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceReceived extends Model
{
    protected $table = 'purchase_invoice_received';

    protected $fillable = [
        'purchase_invoice_id',
        'received_notes',
        'custom_duty',
        'vat',
        'supplementary_duty',
        'thc',
        'container_handling',
        'custom_clearance',
        'documentation_charges',
        'truck_cost',
        'warehouse_receiving',
        'inspection_qc',
        'packaging_labeling',
        'fuel_toll',
        'status',
    ];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceReceivedItem::class, 'received_id');
    }

    public function hscode()
    {
        return $this->belongsTo(HSCode::class);
    }
}
