<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengepul extends Model
{
    protected $fillable = ['kode', 'nama_pengepul', 'no_telepon', 'alamat', 'status'];

    public function hargaPengepuls() {
        return $this->hasMany(HargaPengepul::class, 'pengepul_id');
    }
}
