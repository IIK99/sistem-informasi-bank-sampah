<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setoran_sampahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_setoran', 50)->unique();
            
            // Relasi ke User
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Nasabah penyetor
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete(); // Petugas penimbang
            
            // Relasi ke Master Data Sampah & Satuan
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampahs')->cascadeOnDelete();
            $table->foreignId('satuan_id')->constrained('satuans')->cascadeOnDelete();
            
            // Perhitungan Transaksi
            $table->decimal('jumlah', 8, 2); // Jumlah (bisa berupa Kg, Pcs, Liter, dll tergantung satuannya)
            $table->decimal('harga_satuan', 12, 2); // Harga per Kg/Pcs saat transaksi disetor
            $table->decimal('total_harga', 12, 2); // jumlah * harga_satuan (nominal yang masuk ke saldo nasabah)
            
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran_sampahs');
    }
};