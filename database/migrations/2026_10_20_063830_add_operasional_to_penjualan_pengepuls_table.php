<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan_pengepuls', function (Blueprint $table) {
            $table->decimal('biaya_operasional', 12, 2)->default(0)->after('total_harga');
            $table->string('keterangan_operasional')->nullable()->after('biaya_operasional');
            $table->decimal('total_kas_bersih', 14, 2)->default(0)->after('keterangan_operasional');
        });
    }

    public function down(): void
    {
        Schema::table('penjualan_pengepuls', function (Blueprint $table) {
            $table->dropColumn(['biaya_operasional', 'keterangan_operasional', 'total_kas_bersih']);
        });
    }
};