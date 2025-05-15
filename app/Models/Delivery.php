<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Delivery extends Model
{
    protected $table = 'deliveries';

    protected $fillable = [
        'sale_id',
        'user_id',
        'status',
        'verified',
        'verification',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(User::class);
    }
    public function getVerificationPhotoUrlAttribute()
    {
        return $this->verification ? asset('storage/' . $this->verification) : null;
    }
}
