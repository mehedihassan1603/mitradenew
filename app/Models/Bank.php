<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = ['name', 'branch', 'swift_code', 'contact_person', 'phone', 'email'];
}
