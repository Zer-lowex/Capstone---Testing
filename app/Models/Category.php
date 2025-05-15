<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'prefix',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->prefix = self::generatePrefix($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) { // Only update prefix if name changes
                $category->prefix = self::generatePrefix($category->name);
            }
        });
    }

    private static function generatePrefix($name)
    {
        $words = explode(' ', trim($name)); // Split words by space
        $prefixParts = [];

        foreach ($words as $word) {
            $word = strtoupper(preg_replace('/[AEIOU]/i', '', $word)); // Remove vowels and convert to uppercase
            $prefixParts[] = substr($word, 0, 5); // Limit each part to 5 characters
        }

        $prefix = implode('_', $prefixParts); // Join with '-'

        // Ensure uniqueness by appending a number if needed
        $basePrefix = $prefix;
        $count = 1;
        while (self::where('prefix', $prefix)->exists()) {
            $prefix = $basePrefix . '_' . $count;
            $count++;
        }

        return $prefix;
    }
}
