<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSetoranSampah extends Model
{
    use HasFactory;

    protected $table = 'detail_setoran_sampahs';

    protected $fillable = [
        'setoran_sampah_id',
        'jenis_sampah_id',
        'satuan_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function setoran()
    {
        return $this->belongsTo(SetoranSampah::class, 'setoran_sampah_id');
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