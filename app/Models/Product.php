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
        'harga' => 'decimal:2',
        'is_featured' => 'boolean',
        'stok' => 'integer',
    ];

    // 4. Relasi ke Cart (Sudah benar)
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // 5. Tambahkan relasi ke OrderItem (karena di controller kamu melakukan join/query ke OrderItem)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
}