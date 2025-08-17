<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exporter extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address'];
}
