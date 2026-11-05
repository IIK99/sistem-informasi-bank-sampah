<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanPengepul extends Model
{
    use HasFactory;

    protected $table = 'penjualan_pengepuls';

    protected $fillable = [
        'kode_penjualan',
        'pengepul_id',
        'jenis_sampah_id',
        'satuan_id',
        'user_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'biaya_operasional',
        'keterangan_operasional',
        'total_kas_bersih',
        'catatan',
    ];

    public function details()
    {
        return $this->hasMany(DetailPenjualanPengepul::class, 'penjualan_pengepul_id');
    }

    public function pengepul()
    {
        return $this->belongsTo(Pengepul::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}