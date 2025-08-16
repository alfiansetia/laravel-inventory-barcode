<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['purchase_item_id'])) {
            $query->where('purchase_item_id', $filters['purchase_item_id']);
        }
        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($barcode) {
            $this->noted('Barcode Created');
        });
    }

    public function noted($message)
    {
        BarcodeActivity::create([
            'barcode_id'    => $this->id,
            'time'          => now(),
            'desc'          => $message,
        ]);
    }

    public function activities()
    {
        return $this->hasMany(BarcodeActivity::class)->latest('time');
    }

    public function purchase_item()
    {
        return $this->belongsTo(PurchaseItem::class);
    }
}
