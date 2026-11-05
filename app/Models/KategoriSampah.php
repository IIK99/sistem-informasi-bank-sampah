<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSampah extends Model
{
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function jenisSampahs() {
        return $this->hasMany(JenisSampah::class, 'kategori_id');
    }
}
