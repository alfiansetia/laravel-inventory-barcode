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
}
