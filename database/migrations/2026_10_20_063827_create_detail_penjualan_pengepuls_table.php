<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan_pengepuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_pengepul_id')->constrained('penjualan_pengepuls')->cascadeOnDelete();
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampahs')->cascadeOnDelete();
            $table->foreignId('satuan_id')->constrained('satuans')->cascadeOnDelete();
            $table->decimal('jumlah', 10, 2);      // Berat / kuantitas timbangan
            $table->decimal('harga_satuan', 12, 2); // Tarif beli pengepul
            $table->decimal('subtotal', 14, 2);     // jumlah * harga_satuan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan_pengepuls');
    }
};