<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllChargesList extends Model
{
    use HasFactory;

    protected $table = 'all_charges_lists';
    protected $fillable = ['name'];

}
