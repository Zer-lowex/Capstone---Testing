<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = 
    [
        'product_id',
        'customer_id',
        'type',
        'quantity',
        'expired_at',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
