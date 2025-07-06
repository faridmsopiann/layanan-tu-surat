<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatans'; 

    protected $fillable = ['nama']; 

    // Relasi dengan PengajuanSurat
    public function pengajuanSurats()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
