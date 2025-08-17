<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'pi_number',
        'pi_date',
        'transportation_cost',
        'total_amount',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function attachments()
    {
        return $this->hasMany(PurchaseInvoiceAttachment::class);
    }
}
