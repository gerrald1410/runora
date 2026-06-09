<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Cek dan tambah kolom jika belum ada
            if (!Schema::hasColumn('products', 'category_name')) {
                $table->string('category_name')->nullable()->after('category');
            }
            
            if (!Schema::hasColumn('products', 'sizes')) {
                $table->json('sizes')->nullable()->after('image_url');
            }
            
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('sizes');
            }
            
            // Hapus kolom diskon jika ada (karena tidak dipakai)
            if (Schema::hasColumn('products', 'diskon')) {
                $table->dropColumn('diskon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category_name', 'sizes', 'is_featured']);
        });
    }
};