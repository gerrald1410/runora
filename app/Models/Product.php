<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; 

    protected $fillable = [
        'nama',   
        'kategori', 
        'deskripsi', 
        'harga',         
        'stok',
        'diskon',          
        'gambar',       
        'ukuran', 
        'is_featured',
        'created_at',
        'updated_at'
    ];

    // 3. Sesuaikan juga key di dalam casting data
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