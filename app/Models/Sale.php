<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'user_id',
        'customer_id',
        'delivery_fee',
        'total_amount',
        'paid_amount',
        'sukli',
        'discount',
        'discount_type',
        'customer_address',
        'delivery',
        'status',
        'vat_amount',
        'net_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
    public function getItemsCountAttribute()
    {
        return $this->saleItems->sum('quantity');
    }
    public function items() {
        return $this->hasMany(SaleItem::class);
    }
}
