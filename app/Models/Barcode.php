<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'id'            => 'integer',
            'barcode_id'    => 'integer',
            'available'     => 'boolean',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['purchase_item_id'])) {
            $query->where('purchase_item_id', $filters['purchase_item_id']);
        }
        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }
        if (isset($filters['available'])) {
            $query->where('available', $filters['available']);
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($barcode) {
            $barcode->noted('Barcode Created');
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

    public function markAsAvailable()
    {
        $this->update(['avaliable' => 1]);
        $this->noted('Set to Available');
    }

    public function markAsUnavailable()
    {
        $this->update(['avaliable' => 0]);
        $this->noted('Set to Unavailable');
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
