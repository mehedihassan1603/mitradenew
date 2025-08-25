<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQuotationItem extends Model
{
    protected $fillable = [
        'request_quotation_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relation to parent quotation
    public function quotation()
    {
        return $this->belongsTo(RequestQuotation::class, 'request_quotation_id');
    }

    // Relation to product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
