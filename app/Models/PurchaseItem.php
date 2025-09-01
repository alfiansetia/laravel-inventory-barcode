<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['purchase_id'])) {
            $query->where('purchase_id', $filters['purchase_id']);
        }
    }

    protected $casts = [
        'qty_in' => 'integer',
    ];

    public function getQtyInAttribute($value)
    {
        return intval($value ?? 0);
    }

    public  function isFull()
    {
        return $this->qty_in >= $this->qty_ord;
    }


    public  function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public  function trx()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }

     public  function out()
    {
        return $this->hasMany(OutboundItem::class);
    }
}
