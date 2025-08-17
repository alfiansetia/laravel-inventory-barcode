<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeActivity extends Model
{
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['barcode_id'])) {
            $query->where('barcode_id', $filters['barcode_id']);
        }
    }
}
