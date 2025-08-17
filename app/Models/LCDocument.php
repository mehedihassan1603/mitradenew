<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LCDocument extends Model
{
    protected $table = 'lc_documents';
    protected $fillable = ['lc_id', 'document_type', 'file_path'];

    public function lc()
    {
        return $this->belongsTo(LC::class);
    }
}

