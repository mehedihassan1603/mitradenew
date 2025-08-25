<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LCAdjustment extends Model
{
    protected $table = 'lc_adjustments';
    protected $fillable = ['lc_id', 'supplier_id', 'exporter_id', 'amount', 'description'];

    public function lc()
    {
        return $this->belongsTo(LC::class);
    }
}
