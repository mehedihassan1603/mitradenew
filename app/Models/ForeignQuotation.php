<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignQuotation extends Model
{
    protected $table = 'foreign_quotations';

    protected $fillable = [
        'requisition_id',
        'supplier_id',
        'exporter_id',
        'total_amount',
        'approved',
    ];

    /**
     * The items (products) for this quotation
     */
    public function items()
    {
        return $this->hasMany(ForeignQuotationItem::class, 'foreign_quotation_id');
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
     * Requisition relationship
     */
    public function requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'requisition_id');
    }
}
