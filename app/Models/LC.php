<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LC extends Model
{
    protected $table = 'lcs';
    protected $fillable = [
        'lc_number', 'lc_type', 'exporter_id', 'supplier_id',
        'bank_id', 'lc_amount', 'status', 'issue_date', 'expiry_date', 'remarks'
    ];

    public function documents()
    {
        return $this->hasMany(LCDocument::class,'lc_id');
    }

    public function adjustments()
    {
        return $this->hasMany(LCAdjustment::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function exporter()
    {
        return $this->belongsTo(Exporter::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    public function expenses()
{
    return $this->hasMany(LCExpense::class);
}

public function amendments()
{
    return $this->hasMany(LCAmendment::class);
}

}

