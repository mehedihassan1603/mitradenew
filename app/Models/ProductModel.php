<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    // Table name (optional if it matches the plural snake_case convention)
    protected $table = 'product_models';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'image',
        'product_id',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
