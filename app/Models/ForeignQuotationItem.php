<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignQuotationItem extends Model
{
    protected $table = 'foreign_quotation_items';

    protected $fillable = [
        'foreign_quotation_id',
        'product_id',
        'price',
        'quantity',
        'subtotal',
    ];

    /**
     * Quotation relationship
     */
    public function quotation()
    {
        return $this->belongsTo(ForeignQuotation::class, 'foreign_quotation_id');
    }

    /**
     * Product relationship
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
