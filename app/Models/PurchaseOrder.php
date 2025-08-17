<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    protected $fillable = [
        'foreign_quotation_id',
        'supplier_id',
        'exporter_id',
        'total_amount',
        'status',
    ];

    /**
     * PO items
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    /**
     * Supplier relationship
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Exporter relationship
     */
    public function exporter()
    {
        return $this->belongsTo(Exporter::class, 'exporter_id');
    }

    /**
     * Related foreign quotation
     */
    public function quotation()
    {
        return $this->belongsTo(ForeignQuotation::class, 'foreign_quotation_id');
    }
}
