<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReceived extends Model
{
    use HasFactory;

    protected $fillable = [
        'pi_no',
        'pi_date',
        'notes',
        'supplier',
        'exporter',
        'custom_duty',
        'vat',
        'supplementary_duty',
        'terminal_handling_charges',
        'container_handling',
        'custom_clearance_fees',
        'documentation_charges',
        'carrier_cost',
        'warehouse_receiving_charges',
        'inspection_charge',
        'packaging',
        'fuel_surcharge',
        'received_notes',
        'products','qty',
        'unit_price',
        'total_price',
        'received_qty'
    ];






}
