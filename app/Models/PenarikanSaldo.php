<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanSaldo extends Model
{
    use HasFactory;

    protected $table = 'penarikan_saldos';

    protected $fillable = [
        'kode_penarikan',
        'user_id',
        'nominal',
        'status',
        'catatan_nasabah',
        'catatan_petugas',
        'bukti_transfer',
        'petugas_id',
        'tanggal_proses',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_proses' => 'datetime',
    ];

    // Relasi ke nasabah yang mengajukan
    public function nasabah()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke petugas yang memproses/menyetujui
    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}