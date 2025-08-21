<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = [
        'purchase_requisition_id',
        'request_quotation_id',
        'supplier_id',
        'exporter_id',
        'order_date',
        'status',
        'total_amount',
        'po_id',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function exporter()
    {
        return $this->belongsTo(Supplier::class, 'exporter_id');
    }
}
