<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranSampah extends Model
{
    use HasFactory;

    protected $table = 'setoran_sampahs';

    protected $fillable = [
        'kode_setoran',
        'user_id',
        'petugas_id',
        'jenis_sampah_id',
        'satuan_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'catatan',
    ];

    public function details()
    {
        return $this->hasMany(DetailSetoranSampah::class, 'setoran_sampah_id');
    }

    public function nasabah()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}