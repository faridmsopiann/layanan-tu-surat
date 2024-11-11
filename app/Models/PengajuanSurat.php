<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';

    protected $fillable = ['pemohon_id', 'tanggal_surat', 'asal_surat', 'hal', 'status'];

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'pemohon_id');
    }
}
