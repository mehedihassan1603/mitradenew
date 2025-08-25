<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceReceivedCharge extends Model
{
    protected $table = 'purchase_invoice_received_charges';
    protected $fillable = ['received_id', 'charge_id', 'amount'];

    public function charge()
    {
        return $this->belongsTo(AllChargesList::class, 'charge_id');
    }
}

