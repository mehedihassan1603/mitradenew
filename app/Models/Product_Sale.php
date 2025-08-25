<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_Sale extends Model
{
	protected $table = 'product_sales';
    protected $fillable =[
        "sale_id", "product_id", "product_batch_id", "variant_id", 'imei_number', "qty", "return_qty", "sale_unit_id", "net_unit_price", "discount", "tax_rate", "tax", "total", "is_packing", "is_delivered","topping_id", "unit_price", "unit_discount", "discount_percent", "product_model"
    ];
    public function rating()
{
    return $this->hasOne(Rating::class, 'product_id', 'product_id')
                ->where('customer_id', Auth::id());
}

}
