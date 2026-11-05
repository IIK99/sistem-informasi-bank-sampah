<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualanPengepul extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan_pengepuls';

    protected $fillable = [
        'penjualan_pengepul_id',
        'jenis_sampah_id',
        'satuan_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function penjualan()
    {
        return $this->belongsTo(PenjualanPengepul::class, 'penjualan_pengepul_id');
    }

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}