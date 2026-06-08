<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Route::table('products', function (Blueprint $table) {
        // Menambahkan kolom diskon setelah kolom harga dengan nilai default 0
        $table->integer('diskon')->default(0)->after('harga'); 
    });
}

public function down(): void
{
    Route::table('products', function (Blueprint $table) {
        $table->dropColumn('diskon');
    });
}
};
