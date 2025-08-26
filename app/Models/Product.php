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

    public function purchase_items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function available_barcodes()
    {
        return $this->hasMany(Barcode::class)->where('barcodes.available', 1);
    }

    public function trx()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }

    public function out()
    {
        return $this->hasMany(OutboundItem::class);
    }
}
