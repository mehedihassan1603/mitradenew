<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestQuotation extends Model
{
    use HasFactory;

    protected $table = 'request_quotations';

    protected $fillable = [
        'purchase_requisition_id',
        'supplier_id',
        'product_ids',
        'quantities',
        'prices',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'quantities'  => 'array',
        'prices'      => 'array',
    ];
// Supplier relationship
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Exporter relationship
    public function exporter()
    {
        return $this->belongsTo(Exporter::class, 'exporter_id');
    }

    // Items relationship
    public function items()
    {
        return $this->hasMany(RequestQuotationItem::class, 'request_quotation_id');
    }

    // Requisition relationship
    public function requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'purchase_requisition_id');
    }

}
