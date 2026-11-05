<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    protected $fillable = ['kode_jenis', 'kategori_id', 'satuan_id', 'nama_jenis', 'status'];

    public function kategori() {
        return $this->belongsTo(KategoriSampah::class, 'kategori_id');
    }

    public function satuan() {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }
}
