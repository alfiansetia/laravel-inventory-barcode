<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }
}
