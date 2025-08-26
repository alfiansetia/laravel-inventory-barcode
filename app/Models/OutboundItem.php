<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutboundItem extends Model
{
    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters['outbound_id'])) {
            $query->where('outbound_id', $filters['outbound_id']);
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function outbound()
    {
        return $this->belongsTo(Outbound::class);
    }
}
