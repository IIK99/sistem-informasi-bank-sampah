<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_setoran_sampahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setoran_sampah_id')->constrained('setoran_sampahs')->cascadeOnDelete();
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampahs')->cascadeOnDelete();
            $table->foreignId('satuan_id')->constrained('satuans')->cascadeOnDelete();
            $table->decimal('jumlah', 10, 2);      // Berat timbangan / pcs
            $table->decimal('harga_satuan', 12, 2); // Harga beli ke nasabah
            $table->decimal('subtotal', 14, 2);     // jumlah * harga_satuan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_setoran_sampahs');
    }
};