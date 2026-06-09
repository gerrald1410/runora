<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'category_name',
        'description',
        'price',
        'stock',        // tambah ini
        'discount',     // tambah ini
        'image_url',
        'sizes',
        'is_featured'
    ];

    protected $casts = [
        'sizes' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'stock' => 'integer',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}