<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'category_id',
        'supplier_id',
        'unit_id',
        'cost_price',
        'sell_price',
        'quantity',
        'reorder_level',
        'stock_alert_threshold',
        'expiration_date',
    ];

    public function getFormattedSellPriceAttribute()
    {
        return '₱'.number_format($this->sell_price, 2);
        return '₱'.number_format($this->cost_price, 2);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }   
    
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }  

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/Product Image Not Available.jpg');
        }
        
        // Check if image exists in public/images
        $imagePath = public_path('images/'.$this->image);
        
        if (!file_exists($imagePath)) {
            return asset('images/Product Image Not Available.jpg');
        }
        
        return asset('images/'.$this->image);
    }
}
