<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan'; // Nama tabel yang digunakan

    protected $fillable = ['nama_jabatan']; // Kolom yang dapat diisi

    // Relasi dengan PengajuanSurat
    public function pengajuanSurats()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
