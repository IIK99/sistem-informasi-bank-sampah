<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaPengepul extends Model
{
    protected $fillable = [
        'pengepul_id',
        'jenis_sampah_id',
        'satuan_id',
        'harga_beli',
        'tanggal_berlaku',
    ];

    public function pengepul()
    {
        return $this->belongsTo(Pengepul::class);
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