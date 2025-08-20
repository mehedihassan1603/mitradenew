<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceReceivedItem extends Model
{
    protected $table = 'purchase_invoice_received_items';

    protected $fillable = [
        'received_id',
        'item_id',
        'product_id',
        'received_qty',
        'unit_price'
    ];

    public function received()
    {
        return $this->belongsTo(PurchaseInvoiceReceived::class, 'received_id');
    }
}
