<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    use HasFactory;

    protected $table = 'purchase_requisition';

    protected $fillable = [
        'date',
        'product_id',
        'ref_id',
        'quantities',
    ];

    protected $casts = [
        'product_id' => 'array',
        'quantities' => 'array',
    ];


    public function suppliers()
    {
        return $this->hasMany(RequestQuotation::class, 'purchase_requisition_id');

    }










}



