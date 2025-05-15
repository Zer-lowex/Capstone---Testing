<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    use HasFactory;

    protected $table = 'stock_alerts';

    protected $fillable = [
        'product_id',
        'alert_message',
    ];
}
