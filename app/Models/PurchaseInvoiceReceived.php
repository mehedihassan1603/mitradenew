<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceReceived extends Model
{
    protected $table = 'purchase_invoice_received';

    protected $fillable = [
        'purchase_invoice_id',
        'bl_number',
        'lc_id',
        'transportation_cost',
        'received_notes',
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
    public function charges()
    {
        return $this->hasMany(PurchaseInvoiceReceivedCharge::class, 'received_id');
    }
}
